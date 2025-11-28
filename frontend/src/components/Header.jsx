import { useEffect, useState } from "react";
import { Link } from "react-router-dom";
import { useCart } from '../CartContext';
import { graphql } from '../api/api';
import { GET_CATEGORIES } from "../api/queries/categories";

export default function Header({ activeCategory, onCategoryClick, menuOpen, setMenuOpen }) {
  const { totalItems, toggle, isOpen, setIsOpen } = useCart();
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    const q = GET_CATEGORIES;
    graphql(q)
      .then(data => setCategories(data.categories))
  }, []);

  const menuClick = () => {
    setMenuOpen(!menuOpen); 
    if (!menuOpen) setIsOpen(false);
  }

  const cartBtnClick = () => {
    toggle(); 
    setMenuOpen(false);
  }

  return (
    <div className="header-container">
    <header className="site-header">
      <button className="menu-toggle" onClick={() => menuClick() }>{menuOpen ? 'x' : '☰'}</button>
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
          onClick={() => cartBtnClick() }
        >
          <img src="/cart.png" alt="Cart Image"/>
          {totalItems > 0 ? (<span className="cart-bubble">{totalItems}</span>) : null}
        </button>
      </div>
      
    </header>
    </div>
  );
}
