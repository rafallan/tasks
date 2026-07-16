<?php

namespace App;

enum StatusTarefa: string
{
    case Pendente = 'pendente';
    case EmAndamento = 'em_andamento';
    case Bloqueada = 'bloqueada';
    case Concluida = 'concluida';
    case Cancelada = 'cancelada';
}
