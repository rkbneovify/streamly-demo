////
////  PlayerVC.swift
////  Streamly
////
////  Created by Rohit Singh on 5/27/20.
////  Copyright © 2020 Rohit Singh. All rights reserved.
////
//


import UIKit
import AVKit
import AVFoundation


class PlayerVC: AVPlayerViewController, AVPlayerViewControllerDelegate,AVAssetResourceLoaderDelegate {

    // MARK: Properties
    let overlayView = UIView(frame: CGRect(x: (appDelegate?.window?.frame.width ?? 0) - 400, y: 50, width: 200, height: 200))
    var url:URL?
    var isfromRcent = true
    weak var delegatee:AddRecentData?
    var recentData: RecentData?
    var idS = ""
    lazy var viewModel: EPGViewModel = {
        return EPGViewModel()
    }()

    // MARK: LifeCycle

    override func viewDidLoad() {
        super.viewDidLoad()
        self.initialSetup()

        if !isfromRcent
        {
            self.callAPI()
        }
    }

    override func viewWillDisappear(_ animated: Bool) {
        super.viewDidDisappear(true)


        /// set the data into HomeNewVC
        if !isfromRcent
        {
            guard let data = self.recentData else {
                return
            }
            self.delegatee?.addRecentData(data)
        }
    }

    // MARK: Helpers
    private func initialSetup()
    {
        overlayView.addSubview(UIImageView(image: UIImage(named: "Logo2")))
        contentOverlayView?.addSubview(overlayView)
        // Create the asset instance and the resouce loader because we will be asked
        // for the license to playback DRM protected asset.
        let asset = AVURLAsset(url: url!)
        self.delegate = self
        let queue = DispatchQueue(label: "FP License Acquire")
        asset.resourceLoader.setDelegate(self, queue: queue)

        // Create the player item and the player to play it back in.
        let playerItem = AVPlayerItem(asset: asset)
        playerItem.externalMetadata = sampleExternalMetaData()
        playerItem.navigationMarkerGroups = [sampleNavigationMarkerGroup()]
        playerItem.interstitialTimeRanges = sampleInterstitialTimeRanges()
        player = AVPlayer(playerItem: playerItem)
        player?.play()
    }

    private func callAPI()
    {
        DispatchQueue.global(qos: .background).async
            {
                self.makeApiCallRecent()
                self.initVMRecent()
        }
    }

    private func makeApiCallRecent(){
        viewModel.postRecentData(idS)

    }

    private func initVMRecent() {

        viewModel.recentData = { recentModel in

            self.recentData = recentModel
        }

    }

    @objc func playerItemFailedToPlay(_ notification: Notification) {
        self.player?.play()
        _ = notification.userInfo?[AVPlayerItemFailedToPlayToEndTimeErrorKey] as? Error

    }

    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, shouldWaitForLoadingOfRequestedResource loadingRequest: AVAssetResourceLoadingRequest) -> Bool {
        print("jfjfjf")

        // Getting data for KSM server. Get the URL from tha manifest, we wil need it later as it
        // contains the assetId required for the license request.
        guard
            let url = loadingRequest.request.url else {
                print(#function, "Unable to read URL from loadingRequest")
                loadingRequest.finishLoading(with: NSError(domain: "", code: -1, userInfo: nil))
                return false
        }

        // Link to your certificate on BuyDRM's side.
        guard
            let certificateData = try? Data(contentsOf: URL(string: "https://fp-keyos.licensekeyserver.com/cert/9754e0cfddc682c21216de3eb4d11885.der")!) else {
                print(#function, "Unable to read the certificate data.")
                loadingRequest.finishLoading(with: NSError(domain: "", code: -2, userInfo: nil))
                return false
        }

        // The assetId from the main/variant manifest - skd://xxx, the xxx part. Get the SPC based on the
        // already collected data i.e. certificate and the assetId
        let contentId = url.host
        guard
            let contentIdData = contentId?.data(using: String.Encoding.utf8),
            let spcData = try? loadingRequest.streamingContentKeyRequestData(forApp: certificateData, contentIdentifier: contentIdData, options: nil) else {
                loadingRequest.finishLoading(with: NSError(domain: "", code: -3, userInfo: nil))
                print(#function, "Unable to read the SPC data.")
                return false
        }

        // Prepare to get the license i.e. CKC.
        let requestUrl = "https://fp-keyos.licensekeyserver.com/getkey"
        let stringBody = "spc=\(spcData.base64EncodedString())&assetId=\(contentId ?? "")"
        let postData = NSData(data: stringBody.data(using: String.Encoding.utf8)!)

        // Make the POST request with customdata set to the authentication XML.
        var request = URLRequest(url: URL(string: requestUrl)!)
        request.httpMethod = "POST"
        request.httpBody = postData as Data
        request.allHTTPHeaderFields = ["customdata" : "YOUR AUTHENTICATION XML IN BASE64 FORMAT HERE"]
        let configuration = URLSessionConfiguration.default
        let session = URLSession(configuration: configuration)
        let task = session.dataTask(with: request) { data, response, error in
            if let data = data {

                // The response from the KeyOS MultiKey License server may be an error inside JSON.
                do {
                    let parsedData = try JSONSerialization.jsonObject(with: data) as! [String:Any]
                    let errorId = parsedData["errorid"] as! String
                    let errorMsg = parsedData["errormsg"] as! String
                    print(#function, "License request failed with an error: \(errorMsg) [\(errorId)]")
                } catch let error as NSError {
                    print(#function, "License request failed. Can't parse the response. \(error)")
                }

                // The response from the KeyOS MultiKey License server is Base64 encoded.
                let dataRequest = loadingRequest.dataRequest!

                // This command sends the CKC to the player.
                dataRequest.respond(with: Data(base64Encoded: data)!)
                loadingRequest.finishLoading()
            } else {
                print(#function, error?.localizedDescription ?? "Error during CKC request.")
            }
        }

        task.resume()

        // Tell the AVPlayer instance to wait. We are working on getting what it wants.
        return true
    }
    // MARK: AVPlayerViewControllerDelegate

    func playerViewController(_ playerViewController: AVPlayerViewController, willPresent interstitial: AVInterstitialTimeRange) {
        // Prevent the user from skipping intersitials.
        playerViewController.requiresLinearPlayback = true
    }

    func playerViewController(_ playerViewController: AVPlayerViewController, didPresent interstitial: AVInterstitialTimeRange) {
        /*
         Allow the user to navigate anywhere in the video once an interstitial
         has been played.
         */
        playerViewController.requiresLinearPlayback = false
    }

    // MARK: Meta data

    func sampleExternalMetaData() -> [AVMetadataItem] {
        let titleItem = AVMutableMetadataItem()
        titleItem.identifier = AVMetadataIdentifier.commonIdentifierTitle
        titleItem.value = "Apple Special Event, September 2015" as NSString
        titleItem.extendedLanguageTag = "und"

        let descriptionItem = AVMutableMetadataItem()
        descriptionItem.identifier = AVMetadataIdentifier.commonIdentifierDescription
        descriptionItem.value = "Check out iPhone 6s and iPhone 6s Plus, learn about the powerful iPad Pro, take a look at the new features and bands for Apple Watch, and see the premiere of the all-new Apple TV." as NSString
        descriptionItem.extendedLanguageTag = "und"

        return [titleItem, descriptionItem]
    }

    func sampleNavigationMarkerGroup() -> AVNavigationMarkersGroup {
        let group = AVNavigationMarkersGroup(title: "Announcements", timedNavigationMarkers: [
            timedMetaDataGroupWithTitle("Apple Watch", startTime: 90, endTime: 917),
            timedMetaDataGroupWithTitle("iPad Pro", startTime: 917, endTime: 1691),
            timedMetaDataGroupWithTitle("Apple Pencil", startTime: 1691, endTime: 3105),
            timedMetaDataGroupWithTitle("Apple TV", startTime: 3105, endTime: 4968),
            timedMetaDataGroupWithTitle("iPhone", startTime: 4968, endTime: 7328)
        ])

        return group
    }

    func sampleInterstitialTimeRanges() -> [AVInterstitialTimeRange] {
        let ranges = [
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 728, duration: 53)),
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 1015, duration: 55)),
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 3305, duration: 759)),
            AVInterstitialTimeRange(timeRange: timeRange(withStartTime: 7261, duration: 61))
        ]

        return ranges
    }

    // MARK: Convenience

    func timedMetaDataGroupWithTitle(_ title: String, startTime: TimeInterval, endTime: TimeInterval) -> AVTimedMetadataGroup {
        // Create an `AVMetadataItem` for the title.
        let titleItem = AVMutableMetadataItem()
        titleItem.identifier = AVMetadataIdentifier.commonIdentifierTitle
        titleItem.value = title as NSString
        titleItem.extendedLanguageTag = "und"

        // Create a `CMTimeRange` from the supplied information.
        let range = timeRange(from: startTime, to: endTime)

        return AVTimedMetadataGroup(items: [titleItem], timeRange: range)
    }

    func timeRange(from startTime: TimeInterval, to endTime: TimeInterval) -> CMTimeRange {
        let cmStartTime = CMTimeMakeWithSeconds(Float64(startTime), preferredTimescale: 100)
        let cmEndTime = CMTimeMakeWithSeconds(Float64(endTime), preferredTimescale: 100)
        let timeRange = CMTimeRangeFromTimeToTime(start: cmStartTime, end: cmEndTime)

        return timeRange
    }

    func timeRange(withStartTime startTime: TimeInterval, duration: TimeInterval) -> CMTimeRange {
        let cmStartTime = CMTimeMakeWithSeconds(Float64(startTime), preferredTimescale: 100)
        let cmDuration = CMTimeMakeWithSeconds(Float64(duration), preferredTimescale: 100)
        let timeRange = CMTimeRange(start: cmStartTime, duration: cmDuration)

        return timeRange
    }
}

