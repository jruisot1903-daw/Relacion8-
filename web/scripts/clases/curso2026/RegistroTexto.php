<?php

class RegistroTexto
{
    private string $texto;
    private DateTime $fecha;

    public function __construct(string $texto)
    {
        $this->texto = $texto;
        $this->fecha = new DateTime(); // hora actual del sistema
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function getFecha(): DateTime
    {
        return $this->fecha;
    }

    public function __toString(): string
    {
        return $this->fecha->format('d/m/Y H:i:s') . " - " . $this->texto;
    }

    // No se permiten propiedades dinámicas
    public function __set($nombre, $valor): void
    {
        throw new Exception("No se pueden crear propiedades dinámicas.");
    }
}