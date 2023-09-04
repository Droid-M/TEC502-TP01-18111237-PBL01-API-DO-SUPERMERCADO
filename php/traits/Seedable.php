<?php

namespace php\traits;

use PDOException;

trait Seedable
{
    private static function randomPaymentMethod()
    {
        $paymentMethods = ['pix', 'credit_card', 'cash'];
        return $paymentMethods[array_rand($paymentMethods)];
    }
   
    private static function randomPurchaseStatus()
    {
        $purchaseStatus = ['created', 'paid', 'canceled'];
        return $purchaseStatus[array_rand($purchaseStatus)];
    }

    public static function seedTables()
    {
        $pdo = static::getPDO();
        try {
            // Generate and insert data into the 'cashiers' tables
            for ($i = 1; $i <= 10; $i++) {
                $ip = "192.168." . rand(1, 255) . "." . rand(1, 255);
                $is_blocked = rand(0, 1);

                $sql = "INSERT INTO cashiers (ip, is_blocked) VALUES (:ip, :is_blocked)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':ip', $ip);
                $stmt->bindParam(':is_blocked', $is_blocked);
                $stmt->execute();

                $cashierId = $pdo->lastInsertId(); // Gets the ID of the cashier inserted

                // Generate and insert cashier-related data in the 'purchases' table
                for ($j = 1; $j <= 20; $j++) { // 20 compras por caixa
                    $total_value = number_format(rand(1, 1000) + (rand(0, 99) / 100), 2);
                    $status = static::randomPurchaseStatus();
                    $purchaser_name = "Customer " . $i . "-" . $j;
                    $purchaser_cpf = str_pad(rand(1, 99999999999), 11, "0", STR_PAD_LEFT);
                    $payment_method = self::randomPaymentMethod();

                    $sql = "INSERT INTO purchases (total_value, status, origin_cashier, purchaser_name, purchaser_cpf, payment_method) 
                            VALUES (:total_value, :status, :origin_cashier, :purchaser_name, :purchaser_cpf, :payment_method)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':total_value', $total_value);
                    $stmt->bindParam(':status', $status);
                    $stmt->bindParam(':origin_cashier', $cashierId);
                    $stmt->bindParam(':purchaser_name', $purchaser_name);
                    $stmt->bindParam(':purchaser_cpf', $purchaser_cpf);
                    $stmt->bindParam(':payment_method', $payment_method);
                    $stmt->execute();
                }
            }

            // Generate and insert data into the 'products' table
            for ($i = 1; $i <= 100; $i++) {
                $name = "Product " . $i;
                $stock_quantity = rand(0, 100);
                $price = rand(7, 2100);
                $bar_code = "BAR" . str_pad($i, 5, "0", STR_PAD_LEFT);

                $sql = "INSERT INTO products (name, stock_quantity, bar_code, price) VALUES (:name, :stock_quantity, :bar_code, :price)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':stock_quantity', $stock_quantity);
                $stmt->bindParam(':bar_code', $bar_code);
                $stmt->execute();

                $productId = $pdo->lastInsertId();

                // Generate and insert data into the 'purchase_product' table (many-to-many relationship)
                for ($j = 1; $j <= 5; $j++) { // 5 products per purchase
                    $purchaseId = rand(1, 40);

                    $sql = "INSERT INTO purchase_product (product_id, purchase_id) VALUES (:product_id, :purchase_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':product_id', $productId);
                    $stmt->bindParam(':purchase_id', $purchaseId);
                    $stmt->execute();
                }
            }

            echo "Dados inseridos com sucesso.";
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}
