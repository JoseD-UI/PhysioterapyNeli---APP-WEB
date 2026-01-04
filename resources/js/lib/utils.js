import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

/**
 * Utility para combinar clases de Tailwind de manera inteligente.
 * Soluciona conflictos de clases (ej: 'p-4' vs 'p-2').
 */
export function cn(...inputs) {
  return twMerge(clsx(inputs));
}
