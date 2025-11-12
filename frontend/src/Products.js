//import React from "react";
//import { gql } from '@apollo/client';
//import { useQuery } from '@apollo/client';
import React, { useEffect, useState } from "react";
import parse from "html-react-parser";
//import { useQuery, gql } from "@apollo/client";

const GET_PRODUCTS = `
  query {
    products {
      id
      name
      inStock
      brand
      description
    }
  }
`;

function Products() {
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    fetch("/api/index.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ query: GET_PRODUCTS }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.errors) {
          setError(data.errors[0].message);
        } else {
          setProducts(data.data.products);
        }
        setLoading(false);
      })
      .catch((err) => {
        setError(err.message);
        setLoading(false);
      });
  }, []);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div>
      <h1>Products</h1>
      <ul>
        {products.map((p) => (
          <li key={p.id}>
            {p.name} - {p.brand} - {p.inStock ? "In Stock" : "Out of Stock"} - {" "} 
            {parse(p.description)}
          </li>
        ))}
      </ul>
    </div>
  );
}

export default Products;

/*function Products() {
  const { loading, error, data } = useQuery(GET_PRODUCTS);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error: {error.message}</p>;

  return (
    <div>
      <h1>Products</h1>
      <ul>
        {data.products.map((p) => (
          <li key={p.id}>
            {p.name} - {p.inStock ? "In Stock" : "Out of Stock"}
          </li>
        ))}
      </ul>
    </div>
  );
}*/
