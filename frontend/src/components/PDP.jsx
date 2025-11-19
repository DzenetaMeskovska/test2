import React, { useEffect, useState } from 'react';
import { useParams } from "react-router-dom";
import { graphql } from '../api';
import { useCart } from './CartContext';
import { formatPrice, kebabCase } from '../utils';
import parse from "html-react-parser";

export default function ProductPage() {
  const { id } = useParams();
  //console.log('Product ID:', id);
  const [product, setProduct] = useState(null);
  const [selected, setSelected] = useState({});
  const [activeImage, setActiveImage] = useState(0);
  const { addItem } = useCart();

  useEffect(() => {
    const q = `query { 
      product(id: "${id}") 
      { 
        id 
        name 
        inStock 
        gallery { url } 
        description 
        attributes { 
          name 
          type 
          items { 
            displayValue 
            value 
            } 
        } 
        prices { 
          amount 
          currency { 
            label 
            symbol
          } 
        } 
      } }`;
    graphql(q).then(data=> { setProduct(data.product); 
      const defaults = {};
      data.product.attributes?.forEach(a => defaults[a.name] = a.items?.[0]?.value ?? null);
      //setSelected(defaults);
    }).catch(console.error);
  }, [id]);

  if (!product) return <p>Loading...</p>;

  const addToCart = () => addItem({
    productId: product.id,
    name: product.name,
    image: product.gallery?.[0].url,
    price: product.prices?.[0]?.amount,
    currency: product.prices?.[0]?.currency?.symbol,
    currency_id: product.prices?.[0]?.currency?.id,
    attributes: selected,
    allAttributes: product.attributes,
    qty: 1
  });

  return (
    <div className="pdp">
      <div data-testid="product-gallery" className="gallery">
        <div className="thumbnails">
          {product.gallery.map((img, i) => (
            <img
              key={i}
              src={img.url}
              alt={`Thumbnail ${i + 1}`}
              className={`thumb ${i === activeImage ? 'active' : ''}`}
              onClick={() => setActiveImage(i)}
            />
          ))}
        </div>

        <div className="main-image">
          <img
            src={product.gallery[activeImage]?.url}
            alt={product.name}
          />

          <button
            className="nav prev"
            onClick={() => setActiveImage((activeImage - 1 + product.gallery.length) % product.gallery.length)}
          >
            ‹
          </button>
          <button
            className="nav next"
            onClick={() => setActiveImage((activeImage + 1) % product.gallery.length)}
          >
            ›
          </button>
        </div>
      </div>

      <div className="details">
        <h1>{product.name}</h1>

        {product.attributes.map(attr => (
          <div data-testid={`product-attribute-${kebabCase(attr.name)}`} key={attr.name}>
            <div className="attr-title">{attr.name}:</div>
            <div className="options">
              {attr.items.map(it => {
                const value = it.value;
                //const dataTest = `product-attribute-${kebabCase(attr.name)}-${kebabCase(value)}`;
                const selectedFlag = selected[attr.name] === value;
                return (
                  <button key={it.displayValue}
                    className={`option ${attr.type==='swatch' ? 'swatch' : ''} ${selectedFlag?'selected':''}`}
                    onClick={() => setSelected(prev => ({...prev, [attr.name]: value}))}
                    /*data-testid={dataTest + (selectedFlag ? '-selected' : '')}*/>
                    {attr.type === 'swatch' ? null : it.displayValue}
                    {attr.type === 'swatch' && <span style={{background: value}} className="color-box" />}
                  </button>
                );
              })}
            </div>
          </div>
        ))}
        <div className='attr-title'>Price:</div>
        <div className="price-pdp">{product.prices?.[0]?.currency?.symbol}{formatPrice(product.prices?.[0]?.amount)}</div>

        <button data-testid="add-to-cart" className="add-to-cart" disabled={!product.inStock || product.attributes.some(a => !selected[a.name])} onClick={addToCart}>
          ADD TO CART
        </button>

        <div data-testid="product-description" className="description">{parse(product.description)}</div>
      </div>
    </div>
  );
}
