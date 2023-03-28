export const getCookie = (name: string): string | null => {
  const nameEQ = `${name}=`;
  const cookies = document.cookie.split(';');
  let result: string | null = null;

  cookies.forEach((cookie: string) => {
    let ck: string = cookie;

    while (ck.charAt(0) === ' ') {
      ck = ck.substring(1, ck.length);
    }

    if (ck.indexOf(nameEQ) === 0) {
      result = ck.substring(nameEQ.length, ck.length);
    }
  });

  return result;
};
