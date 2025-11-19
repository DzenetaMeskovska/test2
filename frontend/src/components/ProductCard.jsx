import React from 'react';
import { Link } from 'react-router-dom';
import { formatPrice, kebabCase } from '../utils';
import { useNavigate } from "react-router-dom";
import { useCart } from '../components/CartContext';

export default function ProductCard({ product }) {
  const { addItem } = useCart();

  const defaultAttributes = {};
  product.attributes?.forEach(attr => {
    defaultAttributes[attr.name] = attr.items?.[0]?.value ?? null;
  });

  const quickAdd = (e) => {
    e.stopPropagation();
    addItem({
      productId: product.id,
      name: product.name,
      image: product.gallery?.[0].url || '',
      price: product.prices?.[0]?.amount || 0,
      currency: product.prices?.[0]?.currency?.symbol || '$',
      currency_id: product.prices?.[0]?.currency?.id,
      attributes: defaultAttributes,
      allAttributes: product.attributes,
      qty: 1
    });
  }

  const navigate = useNavigate();

  const cardClick = () => {
    navigate(`/product/${product.id}`);
  };

  return (
    <div className={`product-card ${!product.inStock ? 'oos' : ''}`} data-testid={`product-${kebabCase(product.name)}`} onClick={cardClick}>
      <div className="image-wrap">
        <img src={product.gallery?.[0]?.url} alt={product.name} />
        {!product.inStock && <div className="oos-overlay">OUT OF STOCK</div>}
        {product.inStock && (
          <button className="quick-shop" onClick={quickAdd}><img src="/quick-add.png" alt="Quick add" /></button>)}
      </div>
      <div className="card-title">
        <div className="title">{product.name}</div>
        <div className="price">{product.prices?.[0]?.currency?.symbol}{formatPrice(product.prices?.[0]?.amount)}</div>
      </div>
    </div>
  );
}
