// This file can be replaced during build by using the `fileReplacements` array.
// `ng build` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production: false,
  // baseUrl: 'https://new.staging.streamlytv.com',
  baseUrl: 'http://localhost:8000',
  stripe: {
    publicKey: 'pk_test_51OzBloSA7waI3emKqFtc5EwG8do6KT1gbLAgAy3x4tpoRCjscvMnAf5UHsLpBMAJPfKVw8U0XpRCV2De6fonkom600TWicawcL',
  }
};


// export const environment = {
//   production: false,
//   stripe: {
//     publicKey: 'YOUR_PUBLIC_KEY',
//   }
// };