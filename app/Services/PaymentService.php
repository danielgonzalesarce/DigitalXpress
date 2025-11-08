<?php

namespace App\Services;

class PaymentService
{
    /**
     * Valida formato de tarjeta y simula pago exitoso (DEMO)
     */
    public function validateCreditCard($cardNumber, $expiryMonth, $expiryYear, $cvv, $cardholderName)
    {
        // Limpiar el número de tarjeta
        $cardNumber = preg_replace('/\D/', '', $cardNumber);
        
        // Validar longitud básica (13-19 dígitos)
        if (strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return [
                'valid' => false,
                'message' => 'Número de tarjeta debe tener entre 13 y 19 dígitos'
            ];
        }
        
        // Validar que solo contenga números
        if (!preg_match('/^\d+$/', $cardNumber)) {
            return [
                'valid' => false,
                'message' => 'Número de tarjeta debe contener solo dígitos'
            ];
        }
        
        // Validar CVV (3 o 4 dígitos)
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            return [
                'valid' => false,
                'message' => 'CVV debe tener 3 o 4 dígitos'
            ];
        }
        
        // Validar nombre del titular
        if (empty(trim($cardholderName))) {
            return [
                'valid' => false,
                'message' => 'Nombre del titular es requerido'
            ];
        }
        
        // Validar fecha de vencimiento básica
        if ($expiryMonth < 1 || $expiryMonth > 12) {
            return [
                'valid' => false,
                'message' => 'Mes de vencimiento inválido'
            ];
        }
        
        if ($expiryYear < date('Y')) {
            return [
                'valid' => false,
                'message' => 'Año de vencimiento inválido'
            ];
        }
        
        // Si pasa todas las validaciones de formato, procesar exitosamente
        return [
            'valid' => true,
            'message' => 'Pago procesado exitosamente',
            'transaction_id' => 'TXN_' . time() . '_' . rand(1000, 9999),
            'card_type' => $this->getCardType($cardNumber)
        ];
    }
    
    /**
     * Simula la validación de Yape (DEMO)
     */
    public function validateYape($phoneNumber, $amount)
    {
        // DEMO: Siempre procesar exitosamente
        return [
            'valid' => true,
            'message' => 'Pago con Yape procesado exitosamente (DEMO)',
            'transaction_id' => 'DEMO_YAPE_' . time() . '_' . rand(1000, 9999)
        ];
    }
    
    /**
     * Determinar el tipo de tarjeta (solo para mostrar)
     */
    private function getCardType($cardNumber)
    {
        $firstDigit = substr($cardNumber, 0, 1);
        $firstTwoDigits = substr($cardNumber, 0, 2);
        
        if ($firstDigit === '4') {
            return 'Visa';
        } elseif ($firstTwoDigits >= '51' && $firstTwoDigits <= '55') {
            return 'Mastercard';
        } elseif ($firstTwoDigits === '34' || $firstTwoDigits === '37') {
            return 'American Express';
        } elseif ($firstTwoDigits === '60' || $firstTwoDigits === '65') {
            return 'Discover';
        } else {
            return 'Otra';
        }
    }
}