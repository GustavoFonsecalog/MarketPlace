export interface Product {
  id: number
  name: string
  price: number
  description: string
  category?: string
  image?: string
}

export interface CartItem {
  product: Product
  quantity: number
}

export interface CheckoutRequest {
  items: Array<{
    product_id: number
    quantity: number
  }>
  payment_method: 'pix' | 'credit_card' | 'installments'
  installments?: number
}

export interface CheckoutResponse {
  subtotal: number
  discount: number
  total: number
  installments?: number
}

export type PaymentStrategy = 'pix' | 'credit_card' | 'installments'

export interface PaymentMethod {
  name: string
  description: string
  installments: number | number[]
}

export interface PaymentMethodsResponse {
  pix: PaymentMethod
  credit_card: PaymentMethod
  installments: PaymentMethod
}
