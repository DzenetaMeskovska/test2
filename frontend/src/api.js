export async function graphql(query, variables = {}) {
  const res = await fetch('/api/index.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ query, variables }),
  });

  const text = await res.text();
  //console.log('Raw GraphQL response:', text);

  let json;
  try {
    json = JSON.parse(text);
  } catch (e) {
    throw new Error('Invalid JSON returned from backend: ' + text);
  }

  if (json.errors) throw new Error(json.errors[0].message || 'GraphQL error');
  return json.data;
}
