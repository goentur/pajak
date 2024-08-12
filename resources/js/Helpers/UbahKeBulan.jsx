export const UbahKeBulan = (bulan) => {
  const months = [
    "Januari", "Februari", "Maret", "April", "Mei", "Juni",
    "Juli", "Agustus", "September", "Oktober", "November", "Desember"
  ];

  if (bulan < 1 || bulan > 12) {
    throw new Error("Nomor bulan harus antara 1 dan 12.");
  }

  return months[bulan - 1];
};