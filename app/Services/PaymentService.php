<?php

/**
 * PaymentService
 * 
 * Servicio para validación y procesamiento de pagos.
 * 
 * Funcionalidades:
 * - Validar formato de tarjetas de crédito/débito
 * - Validar pagos con Yape
 * - Simular procesamiento de pagos (modo DEMO)
 * - Generar IDs de transacción
 * 
 * Nota: Este servicio está en modo DEMO y simula los pagos.
 * En producción, debería integrarse con un procesador de pagos real
 * como Stripe, PayPal, o un procesador local.
 * 
 * @author DigitalXpress Team
 * @version 1.0.0
 */

namespace App\Services;

class PaymentService
{
    /**
     * Validar formato de tarjeta de crédito/débito y simular pago (DEMO)
     * 
     * Valida el formato de los datos de la tarjeta:
     * - Número de tarjeta (13-19 dígitos)
     * - Mes y año de vencimiento
     * - CVV (3-4 dígitos)
     * - Nombre del titular
     * 
     * En modo DEMO, siempre retorna éxito si el formato es válido.
     * 
     * @param string $cardNumber Número de tarjeta (puede tener espacios o guiones)
     * @param int $expiryMonth Mes de vencimiento (1-12)
     * @param int $expiryYear Año de vencimiento (>= año actual)
     * @param string $cvv Código de seguridad (3-4 dígitos)
     * @param string $cardholderName Nombre del titular de la tarjeta
     * @return array Array con 'valid' (bool), 'message' (string), 'transaction_id' (string), 'card_type' (string)
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
     * Validar pago con Yape y simular procesamiento (DEMO)
     * 
     * Yape es un método de pago móvil peruano.
     * En modo DEMO, siempre retorna éxito.
     * 
     * @param string $phoneNumber Número de teléfono Yape (formato: 9XXXXXXXX)
     * @param float $amount Monto a pagar
     * @return array Array con 'valid' (bool), 'message' (string), 'transaction_id' (string)
     */
    public function validateYape($phoneNumber, $amount)
    {
        // DEMO: Siempre procesar exitosamente
        // En producción, aquí se integraría con la API de Yape
        return [
            'valid' => true,
            'message' => 'Pago con Yape procesado exitosamente (DEMO)',
            'transaction_id' => 'DEMO_YAPE_' . time() . '_' . rand(1000, 9999)
        ];
    }
    
    /**
     * Determinar el tipo de tarjeta según el número
     * 
     * Identifica el tipo de tarjeta basándose en los primeros dígitos:
     * - Visa: empieza con 4
     * - Mastercard: empieza con 51-55
     * - American Express: empieza con 34 o 37
     * - Discover: empieza con 60 o 65
     * 
     * @param string $cardNumber Número de tarjeta limpio (solo dígitos)
     * @return string Tipo de tarjeta detectado
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