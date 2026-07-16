<?php

namespace App;

enum PrioridadeTarefa: string
{
    case Baixa = 'baixa';
    case Media = 'media';
    case Alta = 'alta';
    case Urgente = 'urgente';
}
