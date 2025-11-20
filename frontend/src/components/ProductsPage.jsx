import React, { useEffect, useState } from 'react';
import ProductCard from '../components/ProductCard';
import { graphql } from '../api';

export default function ProductsPage({category}) {
  //const { name } = useParams();
  //const category = name || 'all';
  const [products, setProducts] = useState([]);

  /*useEffect(() => {
    const q = `query { 
    products { id name inStock gallery { url } description attributes { name type items { displayValue value }} prices { amount currency { label symbol }} category { name }} }`;
    graphql(q).then(data => setProducts(data.products)).catch(console.error);
  }, []);*/

  useEffect(() => {
    const field = category.toLowerCase() === 'all'
      ? "products"
      : category.toLowerCase() + "Products";

    const q = `query {
        ${field} {
          id
          name
          inStock
          gallery { url }
          description
          attributes {
            name
            type
            items { displayValue value }
          }
          prices {
            amount
            currency { label symbol }
          }
          category { name }
        }
      }`;
      /* console.log("Category:", category);
      console.log("Field:", field); */
      graphql(q)
        .then(data => setProducts(data[field]))
        .catch(console.error);
  }, [category]);
  

  //const shown = category && category.toLowerCase() !== 'all' 
  // ? products.filter(p => p.category?.name?.toLowerCase() === category.toLowerCase()) 
  // : products;

  return (
    <main className="product-grid">
      <h2 className="page-category-title">{category ? category.charAt(0).toUpperCase() + category.slice(1) : 'All'}</h2>
      <div className="grid">
        {products.map(p => <ProductCard key={p.id} product={p} />)}
      </div>
    </main>
  );
}
