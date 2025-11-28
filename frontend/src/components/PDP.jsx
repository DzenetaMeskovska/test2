import { useEffect, useState } from 'react';
import { useParams } from "react-router-dom";
import { graphql } from '../api/api';
import { useCart } from '../CartContext';
import { formatPrice, kebabCase } from '../utils';
import parse from "html-react-parser";
import { GET_PRODUCT } from '../api/queries/product';

export default function ProductPage() {
  const { id } = useParams();
  const [product, setProduct] = useState(null);
  const [selected, setSelected] = useState({});
  const [activeImage, setActiveImage] = useState(0);
  const { addItem } = useCart();

  useEffect(() => {
    const q = GET_PRODUCT(id);
    graphql(q).then(data=> { setProduct(data.product); 
      const defaults = {};
      data.product.attributes?.forEach(a => defaults[a.name] = a.items?.[0]?.value ?? null);
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
            onClick={() => setActiveImage(prev => (prev - 1 + product.gallery.length) % product.gallery.length)}
          >
            ‹
          </button>
          <button
            className="nav next"
            onClick={() => setActiveImage(prev => (prev + 1) % product.gallery.length)}
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
                const selectedValue = selected[attr.name] === value;
                return (
                  <button key={it.displayValue}
                    className={`option ${attr.type==='swatch' ? 'swatch' : ''} ${selectedValue ? 'selected' : ''}`}
                    onClick={() => setSelected(prev => ({...prev, [attr.name]: value}))}>
                    {attr.type === 'swatch' 
                    ? <span style={{background: value}} className="color-box" />
                    : it.displayValue}
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
