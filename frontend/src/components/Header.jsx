import React, { useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import { useCart } from '../context/CartContext';
import { graphql } from '../api';

export default function Header({ activeCategory, onCategoryClick }) {
  const { totalItems, toggle } = useCart();
  const [categories, setCategories] = useState([]);
  //const [activeCategory, setActiveCategory] = useState('all');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [menuOpen, setMenuOpen] = useState(false);

  useEffect(() => {
    const q = `query { categories { id name } }`;
    graphql(q)
      .then(data => setCategories(data.categories))
      .catch(err => setError(err.message))
      .finally(() => setLoading(false));
  }, []);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error: {error}</p>;

  return (
    <div className="header-container">
    <header className="site-header">
      <button class="menu-toggle" onClick={() => setMenuOpen(!menuOpen)}>{menuOpen ? 'x' : '☰'}</button>
      <nav className={`categories ${menuOpen ? 'open' : ''}`}>

        {categories.map(cat => (  
          <Link
            key={cat.name}
            to={`/category/${cat.name.toLowerCase()}`}
            data-testid={cat.name === activeCategory ? 'active-category-link' : 'category-link'}
            className={cat.name.toLowerCase() === activeCategory.toLowerCase() ? 'active' : ''}
            onClick={() => onCategoryClick(cat.name)}
            >{cat.name.toUpperCase()}
            </Link>
            
        ))}
      </nav>
      

      <div className="logo"> <img src="/logo.png" alt="Site Logo" /> </div>

      <div>
        <button
          data-testid="cart-btn"
          className="cart-btn"
          onClick={toggle}
        >
          <img src="/cart.png" alt="Cart Image"/>
          {totalItems > 0 ? (<span className="cart-bubble">{totalItems}</span>) : null}
        </button>
      </div>
      
    </header>
    </div>
  );
}
