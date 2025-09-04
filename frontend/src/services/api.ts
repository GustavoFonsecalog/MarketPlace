import axios from 'axios'
import type { CheckoutRequest, CheckoutResponse, PaymentMethodsResponse } from '@/types'

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
  },
})

export const checkoutService = {
  async processCheckout(data: CheckoutRequest): Promise<CheckoutResponse> {
    const response = await api.post<CheckoutResponse>('/checkout', data)
    return response.data
  },

  async getPaymentMethods(): Promise<PaymentMethodsResponse> {
    const response = await api.get<PaymentMethodsResponse>('/payment-methods')
    return response.data
  }
}

export default api
