export const kebabCase = (str = '') =>
  String(str).toLowerCase().replace(/\s+/g, '-').replace(/[^a-z0-9-]/g, '');

export const formatPrice = (amount) =>
  (Number(amount) || 0).toFixed(2);
