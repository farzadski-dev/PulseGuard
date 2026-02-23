import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  stages: [
    { duration: '30s', target: 100 },
    { duration: '1m', target: 1000 },
    { duration: '1m', target: 1000 },
    { duration: '30s', target: 0 }
  ],
  thresholds: {
    http_req_failed: ['rate<0.01'],
    http_req_duration: ['p(95)<250']
  }
};

const url = 'http://localhost:8080/api/transactions/evaluate';

export default function () {
  const payload = JSON.stringify({
    transaction_id: `txn_${__VU}_${__ITER}`,
    amount: 120000,
    currency: 'USD',
    payment_method: 'card',
    device: {
      device_id: `device_${__VU}`,
      ip: '203.0.113.10',
      user_agent: 'k6',
      ip_country: 'US'
    },
    location: {
      country: 'US',
      region: 'CA',
      city: 'San Francisco',
      lat: 37.77,
      lon: -122.41
    },
    occurred_at: new Date().toISOString(),
    metadata: { channel: 'load' }
  });

  const params = {
    headers: {
      'Content-Type': 'application/json',
      'X-Tenant-Id': 'tenant_1',
      'Idempotency-Key': `idem_${__VU}_${__ITER}`
    }
  };

  const res = http.post(url, payload, params);
  check(res, { 'status 200': (r) => r.status === 200 });
  sleep(0.1);
}
