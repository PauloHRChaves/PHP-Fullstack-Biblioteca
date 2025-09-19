<h1>HTTP STATUS</h1>

<h2>1xx: Respostas Informativas</h2>
   * Esses códigos de status indicam que a solicitação foi recebida e o processo continua. A resposta final ainda não foi enviada.

    **100** Continue: O servidor recebeu os cabeçalhos da solicitação e o cliente pode prosseguir com o envio do corpo da solicitação.

    **101** Switching Protocols: O solicitante pediu ao servidor para mudar de protocolo e o servidor está atendendo ao pedido.

<h2>2xx: Respostas de Sucesso</h2>
   * Esses códigos indicam que a solicitação foi recebida, entendida e aceita com sucesso.

    **200** OK: A solicitação foi bem-sucedida. O resultado varia dependendo do método da solicitação (por exemplo, GET, POST).

    **201** Created: A solicitação foi bem-sucedida e um novo recurso foi criado como resultado.

    **202** Accepted: A solicitação foi aceita para processamento, mas o processamento não foi concluído. A solicitação pode ser eventualmente rejeitada.

    **204** No Content: A solicitação foi bem-sucedida, mas a resposta não contém nenhum corpo. Útil para atualizações de páginas que não precisam de uma resposta de corpo.

<h2>3xx: Respostas de Redirecionamento</h2>
   * Esses códigos indicam que o cliente precisa tomar uma ação adicional para concluir a solicitação.

    **301** Moved Permanently: O recurso solicitado foi permanentemente movido para um novo URL. O cliente deve usar este novo URL em futuras solicitações.

    **302** Found: O recurso solicitado foi temporariamente encontrado em um URL diferente. O cliente deve continuar usando o URL original para futuras solicitações.

    **304** Not Modified: Indica que a resposta não foi modificada desde a última vez que o cliente a solicitou. O cliente pode usar uma versão em cache da resposta.

<h2>4xx: Erros do Cliente</h2>
   * Esses códigos de erro indicam que o cliente cometeu um erro. O servidor não pode processar a solicitação devido a algo que o cliente fez (por exemplo, sintaxe incorreta, falta de autenticação).

    **400** Bad Request: O servidor não pode ou não irá processar a solicitação devido a um erro aparente do cliente (por exemplo, sintaxe de solicitação malformada).

    **401** Unauthorized: A solicitação requer autenticação de usuário. O cliente não possui credenciais de autenticação válidas para o recurso de destino.

    **403** Forbidden: O servidor entendeu a solicitação, mas se recusa a autorizá-la. O cliente não tem permissão de acesso ao conteúdo.

    **404** Not Found: O servidor não conseguiu encontrar o recurso solicitado. Pode significar que o URL está incorreto ou que o recurso foi movido ou excluído.

    **405** Method Not Allowed: O método de solicitação (por exemplo, GET, POST) não é suportado para o recurso solicitado.

    **429** Too Many Requests: O usuário enviou muitas solicitações em um determinado período de tempo (limitação de taxa).

<h2>5xx: Erros do Servidor</h2>
   * Esses códigos de erro indicam que o servidor falhou ao atender a uma solicitação aparentemente válida.

    **500** Internal Server Error: O servidor encontrou uma condição inesperada que o impediu de atender à solicitação. É um erro genérico para a maioria dos problemas do lado do servidor.

    **501** Not Implemented: O servidor não suporta a funcionalidade necessária para atender à solicitação. Isso geralmente é visto com métodos HTTP que não são suportados por um servidor.

    **502** Bad Gateway: O servidor, enquanto agia como um gateway ou proxy, recebeu uma resposta inválida de um servidor upstream.

    **503** Service Unavailable: O servidor não está pronto para lidar com a solicitação. As causas comuns incluem um servidor que está inativo para manutenção ou que está sobrecarregado.
