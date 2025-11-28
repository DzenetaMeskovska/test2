import React, { useEffect, useState } from 'react';
import ProductCard from '../components/ProductCard';
import { graphql } from '../api/api';
import { GET_PRODUCTS } from '../api/queries/products';

export default function ProductsPage({category}) {
  const [products, setProducts] = useState([]);

  useEffect(() => {
    const field = category.toLowerCase() === 'all'
      ? "products"
      : category.toLowerCase() + "Products";

    const q = GET_PRODUCTS(field);
      graphql(q)
        .then(data => setProducts(data[field]))
        .catch(console.error);
  }, [category]);

  return (
    <main className="product-grid">
      <h2 className="page-category-title">{category ? category.charAt(0).toUpperCase() + category.slice(1) : 'All'}</h2>
      <div className="grid">
        {products.map(p => <ProductCard key={p.id} product={p} />)}
      </div>
    </main>
  );
}
