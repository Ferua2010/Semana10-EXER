# Lista de Exercícios — Sessões, Cookies e Autenticação

## Parte A: Exercícios Teóricos de Fixação

### 1. Protocolo Stateless

O HTTP é **stateless** porque cada requisição é independente: o servidor não guarda, por padrão, a memória de quem fez a requisição anterior.

Cookies e sessões são necessários para manter o **estado** entre requisições, como manter um usuário logado, um carrinho de compras ou preferências do site. O cookie normalmente guarda o identificador da sessão; o servidor guarda os dados associados a esse identificador. [1]

**Resposta objetiva:**  
HTTP não lembra o usuário entre páginas; cookies/sessões criam essa “memória”.

---

### 2. Cookies vs. Sessões

| Aspecto | Cookie | `$_SESSION` |
|---|---|---|
| Onde fica | No navegador do usuário | No servidor |
| Segurança | Menor, pois pode ser visto/manipulado pelo cliente | Maior, pois os dados ficam no servidor |
| Uso comum | Preferências, tema, lembrar usuário | Login, permissões, dados sensíveis |
| Exemplo | `$_COOKIE['tema']` | `$_SESSION['usuario_id']` |

Para informações confidenciais, use **sessões** (`$_SESSION`). O cookie pode conter apenas um identificador de sessão, e não dados sensíveis como senha, cargo ou permissões. [1]

---

### 3. Flag `HttpOnly`

A configuração:

```php
setcookie('PHPSESSID', session_id(), [
    'httponly' => true
]);
```

impede que o JavaScript acesse o cookie pelo `document.cookie`.

Com `HttpOnly`, mesmo que exista uma falha de **XSS**, o script malicioso não consegue ler o valor do cookie de sessão e enviá-lo para um atacante. O cookie continua sendo enviado automaticamente nas requisições HTTP, mas não fica acessível ao código JavaScript da página. [2]

**Resposta objetiva:**  
`HttpOnly` bloqueia o acesso do JavaScript ao cookie, dificultando o roubo do ID de sessão em ataques XSS.

---

### 4. Flag `SameSite=Lax`

`SameSite=Lax` controla quando o navegador envia o cookie em requisições feitas de outro site.

- Com `Lax`, o cookie é enviado em navegações comuns, como clicar em um link.
- Ele **não é enviado** em requisições cross-site perigosas, como formulários automáticos ou requisições feitas por outro domínio.

Ele protege principalmente contra ataques de **CSRF** (*Cross-Site Request Forgery*), nos quais um site malicioso tenta fazer o navegador da vítima executar uma ação autenticada em outro site. [8]

**Resposta objetiva:**  
`SameSite=Lax` reduz o envio do cookie em requisições entre sites e ajuda a impedir ataques CSRF.

---

### 5. Ataque de Fixação de Sessão

**Session Fixation** acontece quando um atacante consegue definir previamente um ID de sessão para a vítima. Depois que a vítima faz login usando esse mesmo ID, o atacante pode utilizá-lo para acessar a conta.

Por isso, após o login bem-sucedido, é obrigatório gerar um novo ID:

```php
session_regenerate_id(true);
$_SESSION['usuario_id'] = $usuario['id'];
```

`session_regenerate_id(true)` substitui o ID antigo por um novo e mantém os dados da sessão. Assim, qualquer ID conhecido pelo atacante deixa de ser válido. [3][4]

**Resposta objetiva:**  
A função impede que o atacante continue usando um ID de sessão que ele conhecia antes do login.

---

### 6. MD5 e Rainbow Tables

MD5 e SHA256 são algoritmos **rápidos**. Isso é ruim para senhas, porque um atacante pode testar bilhões de combinações por segundo usando GPUs ou servidores especializados.

Uma **Rainbow Table** é uma tabela pré-calculada com senhas comuns e seus hashes. O atacante pega o hash vazado do banco e procura qual senha gerou aquele hash, sem precisar calcular tudo na hora.

**Resposta objetiva:**  
MD5 e SHA256 são rápidos demais para senhas; Rainbow Tables permitem descobrir senhas comuns a partir de hashes previamente calculados.

---

### 7. O Papel do Salt

O **salt** é um valor aleatório gerado para cada usuário e adicionado à senha antes do hash:

```text
hash = algoritmo(senha + salt)
```

Mesmo que dois usuários tenham a senha `123456`, cada um terá um salt diferente e, portanto, hashes diferentes no banco.

Isso impede que o atacante use uma única Rainbow Table para quebrar todas as senhas iguais de uma vez.

**Resposta objetiva:**  
O salt torna cada hash único, mesmo quando as senhas são idênticas.

---

### 8. Argon2id vs. Bcrypt

O **Argon2id** é considerado mais moderno porque permite ajustar:

- Tempo de processamento;
- Uso de memória;
- Paralelismo.

Ele exige bastante **memória RAM**, o que dificulta ataques em massa feitos por GPUs e circuitos ASIC, pois esses dispositivos são otimizados para cálculos rápidos, mas não necessariamente para consumir muita memória.

O **Bcrypt** ainda é seguro, mas o Argon2id oferece melhor resistência contra hardware especializado quando configurado corretamente.

**Resposta objetiva:**  
Argon2id é superior porque exige memória além de processamento, tornando ataques com GPUs e ASICs mais caros e lentos.

---

### 9. Timing Attacks

Um **timing attack** analisa o tempo que o servidor leva para comparar dois valores. Uma comparação comum pode parar assim que encontra a primeira diferença, revelando informações sobre a senha ou o hash.

A função `password_verify()` faz a verificação de forma segura contra esse tipo de ataque:

```php
if (password_verify($senha, $hash)) {
    // login válido
}
```

O PHP recomenda usar `password_verify()` em vez de gerar novamente o hash e comparar com `===`, justamente para evitar ataques baseados em tempo. [6][9]

**Resposta objetiva:**  
`password_verify()` compara o hash de forma segura contra timing attacks; `===` pode revelar informações pelo tempo de execução.