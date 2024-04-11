// This file can be replaced during build by using the `fileReplacements` array.
// `ng build` replaces `environment.ts` with `environment.prod.ts`.
// The list of file replacements can be found in `angular.json`.

export const environment = {
  production: false,
  baseUrl: 'https://new.staging.streamlytv.com',
  // baseUrl: 'http://localhost:8000',
  stripe: {
    publicKey: 'pk_test_51P3YU9SJIkcBUHrJdi42SlDjdbQGCBkW4GiPQVIQV3zCa5gqlHEwaL7oiughaqmy2wcu6D2PnkLp0Hn78iTFK5HX00864N6ycY',
    clientSecret:'sk_test_51P3YU9SJIkcBUHrJqGxak8OiONgkOz5OLkXCj5CQFZuoIm57aUVNvuwFk53TtHDCGwmqhHqf9bWc7sfmfR8eY8rz00DomUNqay'
  }
};


// export const environment = {
//   production: false,
//   stripe: {
//     publicKey: 'YOUR_PUBLIC_KEY',
//   }
// };