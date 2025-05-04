import http from 'k6/http';
import { check, sleep } from 'k6';

// Configuração do teste
export const options = {
    stages: [
      { duration: '1m', target: 8 },  // Simula 20 usuários por 30s
      { duration: '5m', target: 15 },   // Aumenta para 500 usuários
      { duration: '1m', target: 0 },   // Diminui gradualmente
    ],
  };
  

// Função para gerar dados aleatórios
function generateRandomData() {
  const randomString = Math.random().toString(36).substring(7);
  return JSON.stringify({ random_data: `test-${randomString}` });
}

// Teste do endpoint POST /volumes/register
export default function () {
  const url = 'http://web:80/volumes/register'; // "app" é o nome do serviço no compose
  const payload = generateRandomData();
  const headers = { 'Content-Type': 'application/json' };

  // Envia POST
  const postRes = http.post(url, payload, { headers });
  check(postRes, {
    'POST /register status is 200': (r) => r.status === 200,
  });

  // Teste do endpoint GET /volumes/list
  const getRes = http.get('http://web:80/volumes/list');
  check(getRes, {
    'GET /list status is 200': (r) => r.status === 200,
  });

  sleep(0.1); // Intervalo entre requisições
}