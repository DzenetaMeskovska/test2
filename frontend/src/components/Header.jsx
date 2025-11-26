import React, { useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom";
import { useCart } from '../CartContext';
import { graphql } from '../api';

export default function Header({ activeCategory, onCategoryClick, menuOpen, setMenuOpen }) {
  const { totalItems, toggle } = useCart();
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    const q = `query { categories { id name } }`;
    graphql(q)
      .then(data => setCategories(data.categories))
  }, []);

  return (
    <div className="header-container">
    <header className="site-header">
      <button className="menu-toggle" onClick={() => setMenuOpen(!menuOpen)}>{menuOpen ? 'x' : '☰'}</button>
      <nav className={`categories ${menuOpen ? 'open' : ''}`}>

        {categories.map(cat => (  
          <Link
            key={cat.name}
            to={`/${cat.name.toLowerCase()}`}
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
