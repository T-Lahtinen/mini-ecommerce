export interface Product {
    productNo: string;
    name: string;
    description: string | null;
    price: string;
    salePrice: string | null;
    discountPercentage: number | null;
}
