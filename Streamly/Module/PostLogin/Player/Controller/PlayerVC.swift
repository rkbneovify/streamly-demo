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
    var str = ""
    var isAllowedZipCode:Bool?
    var isfromRcent = true
    var isPopAllow = false
    var channelObject:ChannelDetails?
    weak var delegatee:AddRecentData?
    var recentData: RecentData?
    var geoData:GeoFenceAddress?
    var idS = ""
    var timer: Timer?
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

        if !(isAllowedZipCode ?? false)
        {
            if !isPopAllow {
            self.showAlertWithMessage(str)
            }
            else {
                self.playChannel()
            }
            DispatchQueue.global(qos: .background).async
                {
                    self.makeApiGeoFencing()

            }
        }
        else {
            self.playChannel()
        }
    }

    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(true)

    }

    override func viewWillDisappear(_ animated: Bool) {
        super.viewDidDisappear(true)
        self.timer?.invalidate()
        self.timer = nil


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

        overlayView.addSubview(UIImageView(image: UIImage(named: "Logo2")?.alpha(0.5)))
        contentOverlayView?.addSubview(overlayView)

        // Create the asset instance and the resouce loader because we will be asked
        // for the license to playback DRM protected asset.

    }

    private func setuPTimer()
    {
        timer = Timer.scheduledTimer(timeInterval: 15, target: self, selector: #selector(runTimedCode), userInfo: nil, repeats: true)
    }

    @objc func runTimedCode()
    {
        self.makeApiTimer()
    }

    private func playChannel()
    {
        if let url = URL(string: self.channelObject?.url ?? "")
        {
            NotificationCenter.default.addObserver(self,
                                                   selector: #selector(playerItemDidReadyToPlay(notification:)),
                                                   name: .AVPlayerItemNewAccessLogEntry,
                                                   object: player?.currentItem)


            let asset = AVURLAsset(url: url)
            self.delegate = self
            let queue = DispatchQueue(label: "FP License Acquire")
            asset.resourceLoader.setDelegate(self, queue: queue)

            // Create the player item and the player to play it back in.
            let playerItem = AVPlayerItem(asset: asset)
            player = AVPlayer(playerItem: playerItem)
            playerItem.addObserver(self, forKeyPath: "status", options: NSKeyValueObservingOptions(), context: nil)
            NotificationCenter.default.addObserver(self, selector: #selector(playbackStalled(_:)), name: .AVPlayerItemPlaybackStalled, object: playerItem)
            player?.automaticallyWaitsToMinimizeStalling = true
            player?.play()
        }
    }

    @objc func playerItemDidReadyToPlay(notification: Notification) {
            if let _ = notification.object as? AVPlayerItem {
                // player is ready to play now!!
                self.makeApiCallSign()
                self.setuPTimer()
            }
    }

       @objc func playbackStalled(_ notification: Notification?) {
            print("hello")
        }



        override func observeValue(forKeyPath keyPath: String?,
                                   of object: Any?,
                                   change: [NSKeyValueChangeKey : Any]?,
                                   context: UnsafeMutableRawPointer?) {

            // Only handle observations for the playerItemContext
    //        guard context == &playerItemContext else {
    //            super.observeValue(forKeyPath: keyPath,
    //                               of: object,
    //                               change: change,
    //                               context: context)
    //            return
    //        }

            if keyPath == #keyPath(AVPlayerItem.status) {
                let status: AVPlayerItem.Status
                if let statusNumber = change?[.newKey] as? NSNumber {
                    status = AVPlayerItem.Status(rawValue: statusNumber.intValue)!
                } else {
                    status = .unknown
                }

                // Switch over status value
                switch status {
                case .readyToPlay:
                      debugPrint("hello")
                    // Player item is ready to play.
                case .failed:
                    debugPrint("failed")
                    handleErrorWithMessage(player?.currentItem?.error?.localizedDescription, error:player?.currentItem?.error)
                    // Player item failed. See error.
                case .unknown:
                     debugPrint("unknown")
                    handleErrorWithMessage(player?.currentItem?.error?.localizedDescription, error:player?.currentItem?.error)
                    // Player item is not yet ready.
                @unknown default:
                    debugPrint("hello")
                }
            }
        }
    
        func handleErrorWithMessage(_ message: String?, error: Error? = nil) {

            self.makeApiError(message ?? "", "\((error as NSError?)?.code ?? 0)")
        }

    func showAlertWithMessage(_ message: String) {
        DispatchQueue.main.async {
            let alert = UIAlertController(title: appName, message: message, preferredStyle: .actionSheet)
            let okAction = UIAlertAction(title: "Continue", style: .default, handler:{ (action: UIAlertAction!) in
                self.playChannel()
            })
            alert.addAction(okAction)
            self.present(alert, animated: true, completion: nil)
        }
    }

    private func callAPI()
    {
        DispatchQueue.global(qos: .background).async
            {
                self.makeApiCallRecent()
                self.initVMRecent()
        }
    }

    private func makeApiGeoFencing(){
        viewModel.postOutsideAddress()
        initVMGeoData()

    }

    private func initVMGeoData() {

        viewModel.geoData = { geoDataModel in

            self.geoData = geoDataModel

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

    func makeApiCallSign()
    {
        DispatchQueue.global(qos: .background).async
            {
                self.viewModel.postCallSign(self.channelObject?.CallSign ?? "", self.isAllowedZipCode ?? false)
        }
    }

    func makeApiTimer()
       {
           DispatchQueue.global(qos: .background).async
               {
                   self.viewModel.setPlayTimer(self.channelObject?.CallSign ?? "", self.isAllowedZipCode ?? false)
           }
       }

    func makeApiError(_ errorMsg:String, _ errorCode:String)
    {
//        DispatchQueue.global(qos: .background).async
//            {
//                self.viewModel.postgaError(self.channelObject?.CallSign ?? "", self.isAllowedZipCode ?? false,errorMsg,errorCode)
//        }
    }





    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, shouldWaitForLoadingOfRequestedResource loadingRequest: AVAssetResourceLoadingRequest) -> Bool {
        //print("jfjfjf")

        // Getting data for KSM server. Get the URL from tha manifest, we wil need it later as it
        // contains the assetId required for the license request.
        guard
            let url = loadingRequest.request.url else {
                //print(#function, "Unable to read URL from loadingRequest")
                loadingRequest.finishLoading(with: NSError(domain: "", code: -1, userInfo: nil))
                return false
        }

        // Link to your certificate on BuyDRM's side.
        guard
            let certificateData = try? Data(contentsOf: URL(string: Constant.drmCertificateURL)!) else {
                //print(#function, "Unable to read the certificate data.")
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
                //print(#function, "Unable to read the SPC data.")
                return false
        }

        // Prepare to get the license i.e. CKC.
        let requestUrl = Constant.drmRequestURL
        let stringBody = "spc=\(spcData.base64EncodedString())&assetId=\(contentId ?? "")"
        let postData = NSData(data: stringBody.data(using: String.Encoding.utf8)!)

        // Make the POST request with customdata set to the authentication XML.
        var request = URLRequest(url: URL(string: requestUrl)!)
        request.httpMethod = "POST"
        request.httpBody = postData as Data
        request.allHTTPHeaderFields = ["customdata" : Constant.drmXMl]
        let configuration = URLSessionConfiguration.default
        let session = URLSession(configuration: configuration)
        let task = session.dataTask(with: request) { data, response, error in
            if let data = data {

                // The response from the KeyOS MultiKey License server may be an error inside JSON.
                do {
                    let parsedData = try JSONSerialization.jsonObject(with: data) as! [String:Any]
                    _ = parsedData["errorid"] as! String
                    _ = parsedData["errormsg"] as! String
                    //print(#function, "License request failed with an error: \(errorMsg) [\(errorId)]")
                } catch let _ as NSError {
                    //print(#function, "License request failed. Can't parse the response. \(error)")
                }

                // The response from the KeyOS MultiKey License server is Base64 encoded.
                let dataRequest = loadingRequest.dataRequest!

                // This command sends the CKC to the player.
                if let data_temp =  Data(base64Encoded: data)
                {
                    dataRequest.respond(with: data_temp)
                }
                loadingRequest.finishLoading()
            } else {
                //print(#function, error?.localizedDescription ?? "Error during CKC request.")
            }
        }

        task.resume()

        // Tell the AVPlayer instance to wait. We are working on getting what it wants.
        return true
    }

    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, shouldWaitForResponseTo authenticationChallenge: URLAuthenticationChallenge) -> Bool {
        print("0")
        return true
    }

    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, didCancel authenticationChallenge: URLAuthenticationChallenge) {
        print("1")
    }

    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, didCancel loadingRequest: AVAssetResourceLoadingRequest) {
        print("2")
    }


    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, shouldWaitForRenewalOfRequestedResource renewalRequest: AVAssetResourceRenewalRequest) -> Bool {
        print("4")
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
}

