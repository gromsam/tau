const TOKEN_KEY = 'auth_token'

function getCookieOptions(): string {
  const isSecure = typeof window !== 'undefined' && window.location.protocol === 'https:'
  const hostname = typeof window !== 'undefined' ? window.location.hostname : ''
  const maxAge = 60 * 60 * 24 * 7 // 7 days

  const parts = [
    `path=/`,
    `max-age=${maxAge}`,
    `samesite=strict`,
  ]

  if (isSecure) {
    parts.push('secure')
  }

  if (hostname && !hostname.startsWith('localhost') && !hostname.startsWith('127.')) {
    parts.push(`domain=${hostname}`)
  }

  return parts.join('; ')
}

export function setAuthToken(token: string): void {
  if (typeof document === 'undefined') return
  document.cookie = `${TOKEN_KEY}=${encodeURIComponent(token)}; ${getCookieOptions()}`
}

export function getAuthToken(): string | null {
  if (typeof document === 'undefined') return null
  const match = document.cookie.match(new RegExp(`(?:^|; )${TOKEN_KEY}=([^;]*)`))
  if (!match) return null
  try {
    return decodeURIComponent(match[1])
  } catch {
    return null
  }
}

export function removeAuthToken(): void {
  if (typeof document === 'undefined') return
  const hostname = window.location.hostname
  const domainPart = hostname && !hostname.startsWith('localhost') && !hostname.startsWith('127.')
    ? `; domain=${hostname}`
    : ''
  document.cookie = `${TOKEN_KEY}=; path=/; max-age=0${domainPart}`
}
