# Roadmap do Slate

Este documento acompanha o que planejamos implementar para tornar o Slate uma ferramenta geral de agendamentos para profissionais e clínicas. O README principal continua dedicado à descrição básica e à execução do projeto.

## Novo posicionamento

Apresentar o Slate principalmente como uma ferramenta de **organização de agendamentos para profissionais de saúde e consultórios**. A agenda é o fluxo principal; o cadastro de pacientes dá suporte ao atendimento; fichas clínicas, incluindo anamnese, são um recurso complementar e opcional.

Uma mensagem possível para a página inicial:

> Organize seus agendamentos com facilidade. Acompanhe seus pacientes e, quando precisar, registre fichas clínicas em um só lugar.

## Estado atual observado no projeto

- Há uma agenda para o profissional, configuração de horários e uma página pública para solicitação de agendamentos.
- O projeto possui modelos de pacientes e consultas, mas o fluxo de agendamento e o cadastro ainda precisam de melhorias para sustentar uma experiência completa.
- A tela de fichas contém conteúdo de exemplo; a gestão completa de fichas clínicas não deve ser apresentada como pronta.
- A tela de criação de consulta está vazia, e áreas de histórico e notas de paciente ainda precisam ser conectadas à persistência real.
- A estrutura atual parece voltada a um profissional individual. Suporte a clínicas com equipes, unidades ou salas requer decisões de produto e mudanças no modelo de dados.

## Roadmap de implementação

As caixas marcadas representam trabalho concluído. A ordem abaixo prioriza deixar cadastro e agendamento úteis antes de expandir para prontuário e monetização.

### Etapa 1 — Cadastro e configuração da conta

- [ ] Definir os tipos de conta suportados inicialmente: profissional autônomo e consultório/clínica.
- [ ] Melhorar o cadastro para pedir nome, e-mail, senha e tipo de atendimento/negócio, sem solicitar dados clínicos.
- [ ] Após criar a conta, conduzir a configuração inicial: nome público, telefone de contato, área de atuação e fuso horário.
- [ ] Permitir configurar duração padrão das consultas, dias e horários de atendimento e intervalos/bloqueios.
- [ ] Permitir editar essas informações depois nas configurações.
- [ ] Ajustar validação, mensagens de erro e estados de carregamento do cadastro e da configuração inicial.

### Etapa 2 — Agendamento como fluxo principal

- [ ] Implementar a criação de consulta pela agenda e corrigir a tela de criação atualmente vazia.
- [ ] Permitir encontrar um paciente existente ou cadastrar um paciente rapidamente durante o agendamento.
- [ ] Definir e aplicar estados consistentes para consultas: aguardando, confirmada, concluída, cancelada e não compareceu.
- [ ] Impedir conflito de horários e impedir agendamentos fora da disponibilidade configurada.
- [ ] Implementar reagendamento e cancelamento com atualização clara na agenda.
- [ ] Revisar o agendamento público: mostrar horários disponíveis, coletar somente dados de contato necessários e confirmar o pedido ao paciente.
- [ ] Exibir de forma clara se o agendamento público é confirmado automaticamente ou depende da aprovação do profissional.
- [ ] Melhorar a agenda para visualizar consultas por dia/semana e acessar rapidamente os detalhes.

### Etapa 3 — Cadastro e gestão de pacientes

- [ ] Definir os dados básicos necessários do paciente e torná-los consistentes entre cadastro manual e agendamento público.
- [ ] Permitir editar os dados de contato e pesquisar/filtrar pacientes.
- [ ] Exibir histórico real de consultas do paciente, conectado aos dados persistidos.
- [ ] Persistir notas gerais do paciente somente após definir claramente que não são prontuário clínico.
- [ ] Revisar remoção de pacientes e consultas para evitar perda inesperada de histórico.

### Etapa 4 — Suporte a consultórios e clínicas

- [ ] Validar com usuários se o produto inicial atende profissionais individuais ou se clínicas precisam entrar já na primeira versão.
- [ ] Definir o modelo de organização: conta da clínica, membros/profissionais, agendas, unidades e salas.
- [ ] Implementar convites, papéis e permissões antes de permitir acesso compartilhado a pacientes.
- [ ] Garantir que cada profissional possa ter disponibilidade própria e que a clínica consiga acompanhar a agenda geral.

### Etapa 5 — Fichas clínicas opcionais

- [ ] Manter a agenda utilizável sem criar ou preencher anamnese.
- [ ] Definir separação entre dados de contato do paciente e dados clínicos sensíveis.
- [ ] Implementar ficha clínica/anamnese vinculada ao paciente e, quando apropriado, a uma consulta.
- [ ] Permitir modelos de ficha adequados a diferentes áreas de atuação.
- [ ] Definir controles de acesso, histórico de alterações, retenção e exclusão antes de armazenar informações clínicas.
- [ ] Avaliar exportação e impressão como recursos futuros, sem anunciá-los antes da implementação.

### Etapa 6 — Lembretes e planos

- [ ] Validar se pacientes e profissionais precisam de lembretes e por quais canais antes de escolher uma integração.
- [ ] Implementar lembretes e acompanhar entrega/falha antes de divulgar esse recurso.
- [ ] Medir uso e valor percebido dos fluxos essenciais de cadastro e agendamento.
- [ ] Só então testar uma oferta paga para recursos avançados, como múltiplos profissionais, personalização de fichas, lembretes e relatórios.
- [ ] Definir limites, preços, cobrança e cancelamento antes de anunciar planos.

## Critérios para considerar a primeira versão pronta

- Um profissional consegue criar conta, configurar sua disponibilidade e compartilhar o link público.
- Um paciente consegue solicitar um horário disponível sem preencher informações clínicas.
- O profissional consegue confirmar, reagendar ou cancelar sem criar conflito de agenda.
- O cadastro e histórico básico do paciente permanecem consistentes entre agendamento público e manual.
- A landing page descreve somente recursos disponíveis e deixa claro que ficha clínica/anamnese é opcional.

## Recomendações de produto e comunicação

- Começar por profissionais autônomos e pequenos consultórios. Antes de prometer suporte amplo a clínicas, definir equipes, unidades, salas e permissões.
- Tratar como hipótese, não decisão: manter agendamento essencial acessível e cobrar no futuro por recursos avançados. Validar uso e disposição a pagar primeiro.
- Revisar privacidade antes de ampliar a coleta de dados: definir quais informações são coletadas, quem pode acessá-las, por quanto tempo são mantidas e como são removidas.
- Evitar prometer plano gratuito, exportação, impressão, lembretes ou recursos de equipe enquanto não estiverem implementados e confirmados.

## Direção da landing page

- Colocar agenda, horários e link de agendamento no título e nos primeiros benefícios.
- Descrever cadastro de pacientes como suporte à rotina de consultas.
- Apresentar anamnese como complemento opcional, sem torná-la requisito para começar.
- Usar depoimentos apenas quando forem reais e autorizados; enquanto isso, descrever recursos do produto.
- Informar com clareza o que existe hoje e o que está em avaliação para versões futuras.
