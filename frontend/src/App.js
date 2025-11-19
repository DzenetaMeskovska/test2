import React, { useState, useEffect } from "react";
import { BrowserRouter as Router, Routes, Route, useNavigate } from 'react-router-dom';
import { CartProvider } from './components/CartContext';
import Header from './components/Header';
import ProductsPage from './components/ProductsPage';
import ProductPage from './components/PDP';
import CartOverlay from './components/CartOverlay';
import './styles.css'; 
import './App.css'; 

export default function App() {
  const [activeCategory, setActiveCategory] = useState(() => {
    return localStorage.getItem('selectedCategory') || 'all';
  });

  const [menuOpen, setMenuOpen] = useState(false);

  /*useEffect(() => {
    localStorage.setItem('selectedCategory', activeCategory);
  }, [activeCategory]);*/

  const handleCategoryClick = (category) => {
    setActiveCategory(category);
    localStorage.setItem("selectedCategory", category);
    if (menuOpen) {setMenuOpen(!menuOpen)}
  };

  return (
    <CartProvider>
    <Router>
      <Header
        activeCategory={activeCategory}
        onCategoryClick={handleCategoryClick}
        menuOpen={menuOpen}
        setMenuOpen={setMenuOpen}
      />
      <div className="App">
      
      <Routes>
        <Route path="/" element={<ProductsPage category={activeCategory}  />} />
        <Route path="/category/:name" element={<ProductsPage category={activeCategory}  />} />
        <Route path="/product/:id" element={<ProductPage />} />
      </Routes>
      <CartOverlay />
      </div>
    </Router>
    </CartProvider>
  );
}

//export default App;

