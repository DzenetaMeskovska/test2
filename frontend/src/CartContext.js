import React, { createContext, useContext, useEffect, useState } from 'react';

const CartContext = createContext();

export function useCart() { return useContext(CartContext); }

const LOCAL_KEY = 'scandi_cart_v1';

export function CartProvider({ children }) {
  const [items, setItems] = useState(() => {
    try {
      return JSON.parse(localStorage.getItem(LOCAL_KEY)) || [];
    } catch { return []; }
  });
  const [isOpen, setIsOpen] = useState(false);

  useEffect(() => {
    localStorage.setItem(LOCAL_KEY, JSON.stringify(items));
  }, [items]);

  function addItem(newItem) {
    const idx = items.findIndex(i =>
      i.productId === newItem.productId &&
      JSON.stringify(i.attributes) === JSON.stringify(newItem.attributes)
    ); 
    if (idx >= 0) { 
      const copy = [...items];
      copy[idx].qty = (copy[idx].qty || 1) + (newItem.qty || 1); 
      setItems(copy);
    } else {
      setItems(prev => [...prev, { ...newItem, qty: newItem.qty || 1 }]);
    }
    setIsOpen(true);
  }

  function increase(index) {
    setItems(prev => prev.map((it,i) => i===index ? { ...it, qty: (it.qty || 1) + 1 } : it));
  }

  function decrease(index) {
    setItems(prev => {
      const copy = [...prev];
      const currentQty = copy[index].qty || 1;
      if (currentQty <= 1) {
        copy.splice(index,1);
      } else {
        copy[index].qty = currentQty - 1;
      }
      return copy;
    });
  }

  function clear() { setItems([]); }
  function toggle() { setIsOpen(o => !o); }
  const totalItems = items.reduce((s,i) => s + i.qty, 0);
  const cartTotal = items.reduce((s,i) => s + (Number(i.price)||0) * i.qty, 0);
  const totalCurrency = items[0]?.currency;

  return (
    <CartContext.Provider value={{
      items, addItem, increase, decrease, clear, isOpen, setIsOpen, toggle,
      totalItems, cartTotal, totalCurrency
    }}>
      {children}
    </CartContext.Provider>
  );
}
