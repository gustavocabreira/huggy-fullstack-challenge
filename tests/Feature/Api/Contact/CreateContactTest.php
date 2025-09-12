<?php

/**
 * TODO:
 * 1. Criar um contato
 * 1.1.1 Campos obrigatórios: nome (string, max: 255), data de nascimento (date, formato: YYYY-MM-DD, antes de hoje)
 * 1.1.2 Campos opcionais: email (string, max:255), telefone (string, max:10), celular (string, max:11),
 * 1.1.2 endereço (string, max:255), cidade (string, max:255), estado (string, max:2), país (string, max:255), cep (string, max:255)
 * 1.1.3 Um dos campos é obrigatório: email, telefone ou celular
 * 1.1 Deve retornar status 201 (created)
 * 1.2 Deve retornar o contato criado dessa forma: ['data' => $contact]
 * 1.3 Deve salvar o contato no banco de dados
 * 1.4 Deve criar apenas um contato
 * 1.5 Deve enviar a informação para o webhook de que o contato foi criado
 * 1.5.1 Essa informação deve ser processada no background (se possível)
 */