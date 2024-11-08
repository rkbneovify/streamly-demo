
import { Component, OnInit, ElementRef, AfterViewInit, OnDestroy } from '@angular/core';

@Component({
  selector: 'app-video-player',
  template: `<video #videoPlayer controls preload="auto" width="960" data-setup='{"plugins": {"eme": {}}}'></video><h1>Hello Buddy</h1>`,
  standalone: true,
})
export class DashjstestComponent implements OnInit, AfterViewInit, OnDestroy {
  private videoElement!: HTMLVideoElement;
  private certificateUrl = 'https://fairplay.keyos.com/api/v4/getCertificate?certHash=49c8c068c152dfa009ef1c44b99410bb';
  private licenseUrl = 'https://fairplay.keyos.com/api/v4/getLicense';
  private playbackUrl = 'https://d2t3s7h1wasu4l.cloudfront.net/DJCHP/index.m3u8';
  private customData = 'PD94bWwgdmVyc2lvbj0iMS4wIj8+CjxLZXlPU0F1dGhlbnRpY2F0aW9uWE1MPjxEYXRhPjxXaWRldmluZVBvbGljeSBmbF9DYW5QZXJzaXN0PSJmYWxzZSIgZmxfQ2FuUGxheT0idHJ1ZSIvPjxXaWRldmluZUNvbnRlbnRLZXlTcGVjIFRyYWNrVHlwZT0iSEQiPjxTZWN1cml0eUxldmVsPjE8L1NlY3VyaXR5TGV2ZWw+PC9XaWRldmluZUNvbnRlbnRLZXlTcGVjPjxGYWlyUGxheVBvbGljeSBwZXJzaXN0ZW50PSJmYWxzZSIvPjxMaWNlbnNlIHR5cGU9InNpbXBsZSIvPjxHZW5lcmF0aW9uVGltZT4yMDI0LTExLTA3IDEwOjE3OjM2LjAwMDwvR2VuZXJhdGlvblRpbWU+PEV4cGlyYXRpb25UaW1lPjIwMjQtMTEtMDggMTA6MTc6MzYuMDAwPC9FeHBpcmF0aW9uVGltZT48VW5pcXVlSWQ+NWZmMmE1ZWE1ODljMTJjN2JjYWNiYzI4YzllZDY1YWI8L1VuaXF1ZUlkPjxSU0FQdWJLZXlJZD45NzU0ZTBjZmRkYzY4MmMyMTIxNmRlM2ViNGQxMTg4NTwvUlNBUHViS2V5SWQ+PC9EYXRhPjxTaWduYXR1cmU+bHhva0VVck1rRE8xYUQyTE5yc21INkMzTHJXa0RxcFl0NmFaM01MWTRkOFVCVnZKN1lRQnk1TDNhSENRL1hmeHVkTmJaMnI2d2QrRHoyRG40d1o1ODM0b1o4VTExRVB1czJ2dFgxamJxSWRNdVA5MHkwMGxhWUQ4Q3RIUERHb3FscTQvSVQxckxENitGeUZORWxjVVNTMGdyMmFsTEw0MTFqZnM0d28zZlFkcjIwVmpjMkZ4bXBiOWtsZmlLV1BYOTBabXhzT0gxdERKR1FxWjFuMmtMNUo1UktxbmJBZmRBdmNid3lMY0x5WHV4L0hnbitYK1VqL2ZuMTFRZkJ3MDQybDB3Mmw5WFNMb0pWU0JwN1ZFYndOTndiMStnNGVsTGliNGFLMVhMQTVIVU8vWWkxbzZQUTFlL1BndjRHZnlXQVp4SzJlbHpYZ3lJWm9YbjZjNE5nPT08L1NpZ25hdHVyZT48L0tleU9TQXV0aGVudGljYXRpb25YTUw+Cg==';

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
      const contentId = this.extractContentIdFromInitData(initData);
      const session = this.videoElement.mediaKeys!.createSession();
      session.addEventListener('message', async (msgEvent) => {
        const license = await this.getLicenseResponse(msgEvent.message, contentId);
        if (license) await session.update(license);
      });
      session.generateRequest(initDataType, initData).catch(e => console.error('Failed to generate request:', e));
    }
  }

  private extractContentIdFromInitData(initData: ArrayBuffer): string {
    const uint8Array = new Uint8Array(initData);
    const initDataStr = new TextDecoder().decode(uint8Array);

    // Extract the part after "skd://"
    const contentIdMatch = initDataStr.match(/^skd:\/\/(.+)/);
    if (contentIdMatch && contentIdMatch[1]) {
        const contentId = contentIdMatch[1];
        console.log('Extracted Content ID:', contentId);
        return contentId;
    }

    console.warn('Content ID not found in initData.');
    return '';
  }
  private arrayBufferToBase64(buffer: ArrayBuffer): string {
    const bytes = new Uint8Array(buffer);
    let binary = '';
    for (let i = 0; i < bytes.byteLength; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return btoa(binary);
  }

  private async getLicenseResponse(spcMessage: ArrayBuffer, contentId: string): Promise<Uint8Array | null> {
    const spcBase64 = this.arrayBufferToBase64(spcMessage);

    const bodyData = `spc=${spcBase64}&assetId=${contentId}`;
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
}


