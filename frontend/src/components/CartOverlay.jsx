import React from 'react';
import { useCart } from '../CartContext';
import { formatPrice, kebabCase } from '../utils';
import { graphql } from '../api/api';
import { PLACE_ORDER } from '../api/mutations/placeOrder';

export default function CartOverlay() {
  const { items, increase, decrease, isOpen, setIsOpen, cartTotal, totalItems, clear, totalCurrency } = useCart();
  const close = () => setIsOpen(false);

  if (!isOpen) return null;

  const handlePlaceOrder = async () => {
    if (items.length === 0) return;

    const total = items.reduce((sum, i) => sum + (i.price || 0) * (i.qty || 0), 0);

    const currency = Number(items[0]?.currency_id) || 1;

    const itemsStr = items.map(i => {
      const productId = String(i.productId || "");
      const price = Number(formatPrice(i.price) || 0);
      const quantity = parseInt(i.qty || 0, 10);
      const attributes = JSON.stringify(i.attributes || {}).replace(/"/g, '\\"');
    
      return `{ 
        productId: "${productId}", 
        price: ${price}, 
        quantity: ${quantity}, 
        attributes: "${attributes}" 
      }`;
    }).join(", ");

    const mutation = PLACE_ORDER(itemsStr, total, currency);

    await graphql(mutation);
    clear();
  };

  return (
    <>
      <div className="overlay" onClick={close} />
      <div className="cart-panel">
        <p className="cart-title"><span className="item-count">My Bag,</span> {totalItems} {totalItems===1? 'Item' : 'Items'}</p>

        <div className="cart-items">
          {items.map((it, idx) => (
            <div key={idx} className="cart-item">
              
              <div className="cart-item-body">
                <div className="cart-item-title">{it.name}</div>
                <div className="cart-item-price">{it.currency}{formatPrice(it.price)}</div>

                {(it.allAttributes || []).map(attr => (
                  <div key={`${idx}-${attr.name}`} className="cart-attribute" data-testid={`cart-item-attribute-${kebabCase(attr.name)}`}>
                    <div className="cart-attr-name">{attr.name}</div>

                    <div className="options">
                      {attr.items.map(opt => {
                        const isSelected = it.attributes?.[attr.name] === opt.value;
                        const isSwatch = attr.type === 'swatch';

                        return (
                          <div
                            key={`${idx}-${attr.name}-${opt.value}`}
                            className={`option-cart ${isSwatch ? 'swatch' : ''} ${isSelected ? 'selected' : ''}`}
                            data-testid={`cart-item-attribute-${kebabCase(attr.name)}-${kebabCase(opt.value)}`}
                          >
                            {isSwatch 
                              ? <span className="color-box" style={{ backgroundColor: opt.value }}></span>
                              : ( <span 
                                    className="attr-option-value" 
                                    data-testid={`cart-item-attribute-${kebabCase(attr.name)}-${kebabCase(opt.value)}${isSelected ? '-selected' : ''}`}>
                                    {opt.value}
                                </span>
                            )}
                          </div>
                        );
                      })}
                    </div>
                  </div>
                ))}
                
              </div>

                <div className="qty-controls">
                  <button className="qty-button" data-testid="cart-item-amount-increase" onClick={()=>increase(idx)}>+</button>
                  <div className="cart-item-amount" data-testid="cart-item-amount">{it.qty}</div>
                  <button className="qty-button" data-testid="cart-item-amount-decrease" onClick={()=>decrease(idx)}>-</button>
                </div>
              <img src={it.image} alt={it.name} className="cart-item-img" />
            </div>
          ))}
        </div>

        <div className="cart-footer">
          <div className="total-container">
          <div className="total-title">Total</div>
          <div data-testid="cart-total" className="cart-total">{totalCurrency}{formatPrice(cartTotal)}</div>
          </div>

            <button onClick={()=>handlePlaceOrder()} disabled={items.length===0} className={items.length===0 ? 'disabled' : 'enabled'}>PLACE ORDER</button>

        </div>
      </div>
    </>
  );
}
