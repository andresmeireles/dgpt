# DGPT - Um chat ai menos perspicaz*

###### dependendo do modelo usado ele pode ser bem mais perspicaz 😁

**dgpt** é uma sistema de chat com ia onde voce pode usar diversos modelos para conversa. Utiliza php com [frankenphp](https://frankenphp.dev) como runtime, [slim framework](https://www.slimframework.com), [twig](https://twig.symfony.com), [tailwind](https://tailwindcss.com). Utiliza o protocolo [mercure](https://mercure.rocks), uma alternativa ao websocket, para mensagens instantâneas no chat. Como motor de ia utiliza o [ollma](https://ollama.ai).

## Setup do projeto

O projeto possui um arquivo **docker-compose** que subirá todos os serviços, a partir disso algumas configurações tem que ser feitas, é necessário ter instalado:

* PHP na versão 8.2 ou superior

Apos isso instale as dependências do projeto com `composer install`.

Depois voce deve copiar o arquivo `.env.example` e renome-lo para `.env` e preencher os campos, um exemplo de configuração:

```
MODE=dev
OLLAMA_HOST=ollama
OLLAMA_PORT=11434
OLLAMA_LLM_PATH=/llm
```

Onde:

* **MODE** define o modo em que a aplicação vai funcionar, mais explicações abaixo.
* **OLLAMA_HOST** o host do servidor ollama, o padrão do projeto é ollama.
* **OLLAMA_PORT** a porta do servidor, o padrão é 11434.
* **OLLAMA_LLM_PATH** é o caminho, a partir do root do projeto, onde os llm's serão armazenados.

Apos isso o projeto esta pronto para ser usado no caminho `https://localhost`.

## Como utilizar o sistema

Como o projeto propriamente instalado e com um modelo instalado é so escrever seu prompt, a velocidade e acurácia da resposta vai depender da sua maquina e do seu modelo. 

## Como adicionar LLM's?

Os LLM's (large language model) sao centro deste projeto. É necessário ter ao menos um LLM para que o chat funcione. Por padrão o projeto nao vem com nenhum LLM, por causa do seu tamanho (eles costumam ser bem grandes).

O primeiro passo para adicionar um llm é baixar um modelo, eles podem ser encontrados no site do [huggingface](https://huggingface.co), os modelos baixados devem ser no formato `gguf`, baixe um que se adéque a sua maquina, um modelo de 2_7B de parâmetros normalmente pede 2G de memoria ram um modelo de 7B necessita de 7 a 8GB e assim por diante.

Com um modelo baixado adicione esse modelo na pasta `llm` ou pasta que voce definiu no arquivo `.env` dentro da pasta crie um arquivo com o mesmo nome do modelo mas sem nenhuma extensão, por exemplo, baixei o modelo `openchat_3.5.Q2_K.gguf` entrao crio um arquivo `openchat_3.5.Q2_K` (sem extensão alguma), o conteúdo deste arquivo deve ser:

```
FROM ./openchat_3.5.Q2_K.gguf
```

Mais opções sobre o que por neste arquivo podem ser encontrados no site do ollama [por link aqui]

Recarrega a pagina do sistema e vera que na barra lateral haverá uma opção chamada `openchat_3.5.Q2_K` com uma opção chamada `Add` aperte neste botão e o modelo sera adicionado ao sistema, isso pode demora um pouco de acordo com sua maquina e do tamanho do modelo. Vários modelos podem ser adicionados ao sistema.

## Como utilizar o worker mode do Frankenphp

Uma das features mais legais do **frankenphp** é o [*worker mode*](https://frankenphp.dev/docs/worker/) um modo apos o primeiro boot a aplicação php fica em memoria, deixando-a bastante eficaz.

Primeiramente, todas as variáveis no arquivo `.env` devem ser passadas para o arquivo `docker-compose.yml`. A inserção de varáveis de ambiente via `.env` nao funciona de forma consistente.

O primeiro passo é no arquivo `.env` mudar o `MODE` para `prod` ou `worker` no arquivo `docker-compose.yml` descomete a linha.

`#  - FRANKENPHP_CONFIG=worker public/index.php`

Depois reinicie o container do php.

Na minha percepção o worker mode nao faz muita diferença nesta aplicação, da sua arquitetura atual, pois ela faz chamadas a outro servidor, porem se a abordagem com a ia for feita de outro modo, usando o llama.cpp por exemplo, onde o processamento é local, acho que o workermode traria mais benefícios, acredito que houve alguma melhora de performance, mas nao tanta nesse caso de uso.  

