export function GET_PRODUCT(id) {
    return `
        query { 
        product(id: "${id}") 
        { 
            id 
            name 
            inStock 
            gallery { url } 
            description 
            attributes { 
            name 
            type 
            items { 
                displayValue 
                value 
                } 
            } 
            prices { 
            amount 
            currency { 
                label 
                symbol
            } 
            } 
        } 
    }`;
}
