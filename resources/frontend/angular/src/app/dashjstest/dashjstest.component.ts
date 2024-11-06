// import {
//   CUSTOM_ELEMENTS_SCHEMA,
//   Component,
// } from '@angular/core';
// import { CardComponent } from '../components/shared/card/card/card.component';
// @Component({
//   selector: 'app-dashjstest',
//   standalone: true,
//   imports: [CardComponent],
//   templateUrl: './dashjstest.component.html',
//   styleUrl: './dashjstest.component.css',
// })
// export class DashjstestComponent {
//   public counter = 1;

//   public handleOnClick(stateCounter: any) {
//     this.counter++;
//   }
// }

import { Component, OnInit, ElementRef, AfterViewInit, OnDestroy } from '@angular/core';

@Component({
  selector: 'app-video-player',
  template: `<video #videoPlayer controls preload="auto" width="960"></video>`,
  standalone: true,
})
export class DashjstestComponent implements OnInit, AfterViewInit, OnDestroy {
  private videoElement!: HTMLVideoElement;
  private certificateUrl = 'https://fairplay.keyos.com/api/v4/getCertificate?certHash=49c8c068c152dfa009ef1c44b99410bb';
  private licenseUrl = 'https://fairplay.keyos.com/api/v4/getLicense';
  private playbackUrl = 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8';
  private customData = 'PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDI0LTExLTA1IDA4OjM5OjEwLjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjQtMTEtMDYgMDg6Mzk6MTAuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+ZGY5MDJmOWE1YzNjMTI5ZGRiZWJmMzA0MDM0MzYwMDU8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+cXdCd2lMVE1GNitBTUpLVUQ2YTVQYXpyeVJjN1lnTllsYVFNT1hORVVyUzlDaWI4OTlGUlVGVGl1bFNRalUyTXlVY3JpWGZaN1Fkd3JzKzF2czFHR2pCc0UxeVh2SXZYMERuK21ackFwSThxWHpzZkpKTUdzNU9GODN4MHRJdUx6Y0tsMWg3RXNZWXF5MVo5YmFwS1g2dnFHYWpXSFJ2dWJ2OGV2Sy9mOFFtQlVlQ0lFKzFNWW5mQjFwcGpxNGNZaGNuY3JSRTV6a0ZWRlFUN1Vjbm1lQ3JBUmtCTHBFaEFON0RheFpkYXJlVGFPUHdnUm5ITmNsSnh2clNwcVNXVHppOE50d0RUeUF4QjNneTIvU3JMWk9TU0J6ZHhNNGwrRDl5dVIxdVVtK3dHZlhKSjBISzlNVkNVTjFpYjVVaDU3TjJDblBMV3gzM21mcktkQSs2V2NRPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg==';

  constructor(private elRef: ElementRef) {}

  ngOnInit(): void {}

  ngAfterViewInit(): void {
    this.videoElement = this.elRef.nativeElement.querySelector('video');
    this.startVideo();
  }

  ngOnDestroy(): void {
    if (this.videoElement) {
      this.videoElement.src = ''; // Clear source on destroy
    }
  }

  private async loadCertificate(): Promise<ArrayBuffer | null> {
    try {
      const response = await fetch(this.certificateUrl);
      return await response.arrayBuffer();
    } catch (e) {
      console.error(`Could not load certificate at ${this.certificateUrl}`, e);
      return null;
    }
  }

  private async startVideo(): Promise<void> {
    const certificate = await this.loadCertificate();
    if (!certificate) {
      console.error('Certificate could not be loaded.');
      return;
    }

    this.videoElement.src = this.playbackUrl;
    this.videoElement.addEventListener('encrypted', (event) => this.onEncrypted(event, certificate));
    this.videoElement.addEventListener('error', (e) => console.error('Video error:', e));
  }

  private async onEncrypted(event: MediaEncryptedEvent, certificate: ArrayBuffer): Promise<void> {
    const initDataType = event.initDataType;
    if (initDataType !== 'skd') {
      console.error(`Received unexpected initialization data type "${initDataType}"`);
      return;
    }

    if (!this.videoElement.mediaKeys) {
      const access = await navigator.requestMediaKeySystemAccess('com.apple.fps', [{
        initDataTypes: [initDataType],
        videoCapabilities: [{ contentType: 'application/vnd.apple.mpegurl', robustness: '' }],
        distinctiveIdentifier: 'not-allowed',
        persistentState: 'not-allowed',
        sessionTypes: ['temporary'],
      }]);

      const mediaKeys = await access.createMediaKeys();
      await mediaKeys.setServerCertificate(certificate);
      await this.videoElement.setMediaKeys(mediaKeys);
    }

    const initData = event.initData;
    if (initData) {
      const session = this.videoElement.mediaKeys!.createSession();
      session.addEventListener('message', async (msgEvent) => {
        const license = await this.getLicenseResponse(msgEvent.message);
        if (license) await session.update(license);
      });
      session.generateRequest(initDataType, initData).catch(e => console.error('Failed to generate request:', e));
    }
  }

  private arrayBufferToBase64(buffer: ArrayBuffer): string {
  const bytes = new Uint8Array(buffer);
  let binary = '';
  for (let i = 0; i < bytes.byteLength; i++) {
      binary += String.fromCharCode(bytes[i]);
  }
  const base64 = btoa(binary); // Encode the binary string as Base64
  console.log('Base64-encoded SPC:', base64); // Log the Base64-encoded SPC
  return base64;
}

private async getLicenseResponse(spcMessage: ArrayBuffer): Promise<Uint8Array | null> {
  const spcBase64 = this.arrayBufferToBase64(spcMessage); // Convert SPC to Base64

  const bodyData = new URLSearchParams({
      spc: spcBase64,
      customdata: this.customData,
  }).toString();

  console.log('Request Body Data:', bodyData); // Log the body data being sent

  try {
      const response = await fetch(this.licenseUrl, {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
              'customdata': this.customData,
          },
          body: bodyData,
      });

      if (!response.ok) {
          console.error(`License request failed with status ${response.status}: ${response.statusText}`);
          return null;
      }

      const responseText = await response.text();
      console.log('Response from License Server:', responseText); // Log the response
      return Uint8Array.from(atob(responseText), c => c.charCodeAt(0));
  } catch (error) {
      console.error('License request failed due to exception:', error);
      return null;
  }
}

  // private async getLicenseResponse(spcMessage: ArrayBuffer): Promise<Uint8Array | null> {
  //   const spcString = btoa(String.fromCharCode(...new Uint8Array(spcMessage)));

  //   try {
  //     const response = await fetch(this.licenseUrl, {
  //       method: 'POST',
  //       headers: {
  //         'Content-Type': 'application/x-www-form-urlencoded',
  //         'customdata': this.customData,
  //       },
  //       body: JSON.stringify({ spc: spcString }),
  //     });

  //     const responseObject = await response.json();
  //     return Uint8Array.from(atob(responseObject.ckc), c => c.charCodeAt(0));
  //   } catch (e) {
  //     console.error('License request failed:', e);
  //     return null;
  //   }
  // }


//   private async getLicenseResponse(spcMessage: ArrayBuffer): Promise<Uint8Array | null> {
//     const spcString = btoa(String.fromCharCode(...new Uint8Array(spcMessage)));

//     try {
//         // **Format 1: JSON format (initial)** //
//         // const response = await fetch(this.licenseUrl, {
//         //     method: 'POST',
//         //     headers: {
//         //         'Content-Type': 'application/json',  // Confirm this is the expected content-type
//         //         'customdata': this.customData,       // Send any required custom data
//         //     },
//         //     body: JSON.stringify({ spc: spcString }),
//         // });

//         // Uncomment this alternative format if Format 1 does not work:

//         // **Format 2: Direct Base64 without JSON**
//         const response = await fetch(this.licenseUrl, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/octet-stream',
//                 'customdata': this.customData,
//             },
//             body: spcString,  // Send the SPC directly if required by the server
//         });


//         if (!response.ok) {
//             console.error('License request failed with status:', response.status);
//             return null;
//         }

//         const responseObject = await response.json();
//         return Uint8Array.from(atob(responseObject.ckc), c => c.charCodeAt(0));
//     } catch (e) {
//         console.error('License request failed:', e);
//         return null;
//     }
// }

// private async getLicenseResponse(spcMessage: ArrayBuffer): Promise<Uint8Array | null> {
//   // Encode SPC to Base64
//   const spcString = btoa(String.fromCharCode(...new Uint8Array(spcMessage)));

//   try {
//       // Prepare headers
//       const headers = new Headers({
//           'Content-Type': 'application/json', // or 'application/octet-stream' if needed
//           'customdata': this.customData,
//           // Add authorization or additional headers here if required, for example:
//           // 'Authorization': 'Bearer YOUR_TOKEN',  // replace YOUR_TOKEN with actual token if needed
//       });

//       // Send request as JSON body (try with JSON body first)
//       let response = await fetch(this.licenseUrl, {
//           method: 'POST',
//           headers,
//           body: JSON.stringify({ spc: spcString }),
//       });

//       // Retry with raw Base64 body if JSON format fails
//       if (!response.ok) {
//           console.warn('Initial JSON request failed. Retrying with raw SPC body format...');
//           response = await fetch(this.licenseUrl, {
//               method: 'POST',
//               headers,
//               body: spcString, // direct SPC string for servers expecting raw Base64 format
//           });
//       }

//       if (!response.ok) {
//           console.error(`License request failed with status ${response.status}: ${response}`);
//           return null;
//       }

//       const responseObject = await response.json();
//       return Uint8Array.from(atob(responseObject.ckc), c => c.charCodeAt(0));
//   } catch (e) {
//       console.error('License request failed due to exception:', e);
//       return null;
//   }
// }


// private async getLicenseResponse(spcMessage: ArrayBuffer): Promise<Uint8Array | null> {
//   const spcBase64 = this.arrayBufferToBase64(spcMessage);  // Convert SPC to Base64

//   // Prepare the data to be sent in application/x-www-form-urlencoded format
//   const bodyData = new URLSearchParams({
//       spc: spcBase64,
//       customdata: this.customData,
//       assetId: "deb4191caa9c42c69fde7c0a282556fe" // Replace with your actual asset ID if it varies
//   }).toString();

//   try {
//       const response = await fetch(this.licenseUrl, {
//           method: 'POST',
//           headers: {
//               'Content-Type': 'application/x-www-form-urlencoded',
//               'customdata': this.customData, // Ensure this is consistent with the example
//           },
//           body: bodyData,
//       });

//       if (!response.ok) {
//           console.error(`License request failed with status ${response.status}: ${response.statusText}`);
//           return null;
//       }

//       // Assuming the response contains the CKC directly in the body
//       const responseText = await response.text();
//       return Uint8Array.from(atob(responseText), c => c.charCodeAt(0));
//   } catch (error) {
//       console.error('License request failed due to exception:', error);
//       return null;
//   }
// }

// private arrayBufferToBase64(buffer: ArrayBuffer): string {
//   const bytes = new Uint8Array(buffer);
//   let binary = '';
//   for (let i = 0; i < bytes.byteLength; i++) {
//       binary += String.fromCharCode(bytes[i]);
//   }
//   return btoa(binary); // Encode the binary string as Base64
// }





}
