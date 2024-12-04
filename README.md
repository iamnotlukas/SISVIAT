# SISVIAT - Sistema de Liberação de Viaturas

## 🎯 Objetivo  
Desenvolver um sistema eficiente e centralizado para gerenciar o processo de solicitação, aprovação e monitoramento da liberação de viaturas em uma intranet militar, visando aumentar a transparência, a organização e a agilidade do processo.

---

## 📋 Descrição  
O **SISVIAT** é um sistema web desenvolvido para atender as necessidades de gestão de viaturas em instalações militares. Ele integra diferentes níveis hierárquicos no fluxo de liberação, desde a solicitação inicial pela Garagem até a aprovação final pelo Chefe de Departamento. Além disso, fornece uma interface intuitiva para acompanhar o status das liberações em tempo real.

---

## ⚙️ Funcionalidades  
- **Cadastro de solicitações de liberação de viaturas** pela Garagem.  
- **Aprovação de solicitações** pelo Chefe de Departamento.  
- **Monitoramento em tempo real** do status de solicitações na sala de estado.  
- **Agendamento de liberações**, permitindo planejamento antecipado.  
- **Interface responsiva**, acessível em dispositivos móveis e desktops.  
- **Histórico de liberações**, para consulta futura.  

---

## 🛠️ Tecnologias Usadas  
- **Back-end:** PHP (utilizando PDO para interação com o banco de dados).  
- **Banco de Dados:** MySQL.  
- **Front-end:** HTML5, CSS3, JavaScript.  
- **Bibliotecas:** TCPDF (para geração de relatórios em PDF).  
- **Servidor:** Ambiente local (XAMPP/WAMP) ou servidor Apache.  

---

## 🌟 Diferenciais  
- Fluxo integrado e hierárquico que automatiza aprovações.  
- Planejamento estratégico com funcionalidade de agendamento.  
- Histórico detalhado para auditorias e consultas.  
- Design simples e funcional, adaptado às demandas militares.  

---

## 🚀 Como Executar  
1. Clone o repositório:  
   ```bash
   git clone https://github.com/seu-usuario/sisviat.git
2. Configure o banco de dados usando o script SQL fornecido em /database/sisviat.sql.
3. Configure o arquivo conexao.php com as credenciais do banco de dados.
4. Hospede o projeto em um servidor local como XAMPP ou WAMP.
5. Acesse o sistema pelo navegador em http://localhost/sisviat.

---
