export const formatRupiah = (amount, withPrefix = true) => {
  if (amount === undefined || amount === null) return "";

  const formatter = new Intl.NumberFormat("id-ID", {
    style: withPrefix ? "currency" : "decimal",
    currency: "IDR",
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  });

  return formatter.format(amount);
};

export const formatDateTime = (dateString) => {
  if (!dateString) return '-';
  const date = new Date(dateString);
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(date);
};