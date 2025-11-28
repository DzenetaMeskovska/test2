export function PLACE_ORDER(itemsStr, total, currency) {
    return `
      mutation {
        placeOrder(
          items: [${itemsStr}],
          total: ${total},
          currency_id: ${currency}
        ){
          id
          total
          currency_id
          items {
            productId
            price
            quantity
            attributes
          }
        }
      }
    `;
}