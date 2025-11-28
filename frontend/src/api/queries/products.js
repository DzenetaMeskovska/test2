export function GET_PRODUCTS(field) {
    return `
        query {
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
}
