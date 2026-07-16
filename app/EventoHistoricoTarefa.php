<?php

namespace App;

enum EventoHistoricoTarefa: string
{
    case TarefaCriada = 'tarefa_criada';
    case ResponsavelAlterado = 'responsavel_alterado';
    case StatusAlterado = 'status_alterado';
    case PrazoAlterado = 'prazo_alterado';
    case PrioridadeAlterada = 'prioridade_alterada';
    case ComentarioAdicionado = 'comentario_adicionado';
    case TarefaConcluida = 'tarefa_concluida';
    case TarefaReaberta = 'tarefa_reaberta';
}
