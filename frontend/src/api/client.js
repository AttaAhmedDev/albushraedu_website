const BASE = '/OnlineLearningPlatform';
export const API_BASE = `${BASE}/api`;
export const ASSET_BASE = BASE;

export async function api(path, options = {}) {
  let { method = 'GET', body, formData, headers = {} } = options;

  const init = {
    method,
    credentials: 'include',
    headers: { ...headers },
  };

  // Send PUT/DELETE as POST + override (some Apache setups block those verbs)
  if (method === 'PUT' || method === 'DELETE') {
    init.headers['X-HTTP-Method-Override'] = method;
    init.method = 'POST';
  }

  if (formData) {
    init.body = formData;
  } else if (body !== undefined) {
    init.headers['Content-Type'] = 'application/json';
    init.body = JSON.stringify(body);
  }

  const res = await fetch(`${API_BASE}/${path.replace(/^\//, '')}`, init);

  const contentType = res.headers.get('content-type') || '';
  if (!contentType.includes('application/json')) {
    if (!res.ok) {
      throw new Error(`Request failed (${res.status})`);
    }
    return res;
  }

  const data = await res.json();
  if (!res.ok || data.success === false) {
    throw new Error(data.error || `Request failed (${res.status})`);
  }
  return data;
}

export function fileUrl(path) {
  return `${API_BASE}/${path.replace(/^\//, '')}`;
}

export function publicAsset(path) {
  return `${ASSET_BASE}/${path.replace(/^\//, '')}`;
}
