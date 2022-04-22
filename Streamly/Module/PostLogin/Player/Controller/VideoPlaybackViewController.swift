/*
 See LICENSE folder for this sample’s licensing information.
 
 Abstract:
 The view controller for managing video playback.
 */

import UIKit
import AVKit
import os

// Change the value of checkParentalControlsExplicitly to compare automatic support for parental controls,
// vs. explicitly calling requestPlaybackRestrictionsAuthorization.
let checkParentalControlsExplicitly = false

class VideoPlaybackViewController: UIViewController, AVPlayerViewControllerDelegate,AVAssetResourceLoaderDelegate {
    
    // The playerViewController is the one currently in use for full-screen playback.
    @objc dynamic var playerViewController: AVPlayerViewController?
    let customInteractiveVideoOverlay = CustomInteractiveVideoOverlay(nibName: "CustomInteractiveVideoOverlay", bundle: nil)
    var filterData:[ChannelDetails]?
    var player: AVQueuePlayer?
    @objc dynamic var pendingPlayerItem: AVPlayerItem?
    var playerItemStatusObservation: NSKeyValueObservation?
    var currentItemErrorObservation: NSKeyValueObservation?
    var isPlaybackActive = false
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
    
    func commonInit() {
        
        playerItemStatusObservation = observe(\.pendingPlayerItem?.status) { (object, change) in
            DispatchQueue.main.async { // Avoid modifying an observed property from directly within an observer.
                let pendingItemStatus = object.pendingPlayerItem?.status
                if pendingItemStatus == AVPlayerItem.Status.readyToPlay {
                    if checkParentalControlsExplicitly {
                        // Explicitly check whether it is okay to play this item.
                        object.pendingPlayerItem?.requestPlaybackRestrictionsAuthorization { (success, error) in
                            if success {
                                object.pendingPlayerItem = nil
                                object.playerViewController?.player = object.player
                                object.player?.rate = 1.0
                            } else {
                                // If the request failed, immediately dismiss our view controller.
                                _ = object._dismiss()
                            }
                        }
                    } else {
                        // Start playback immediately (AVPlayerViewController will request authorization automatically when we provide the player item).
                        object.pendingPlayerItem = nil
                        object.playerViewController?.player = object.player
                        object.player?.rate = 1.0
                    }
                }
            }
        }
        
        currentItemErrorObservation = observe(\.playerViewController?.player?.currentItem?.error) { (object, change) in
            DispatchQueue.main.async {
                if object.playerViewController?.player?.currentItem?.error != nil {
                    // A very simple error handler: Just dismiss the playback UI.
                    // This is probably sufficient for unanticipated failures, or any where the error message is unlikely to help the user solve the problem.
                    _ = object._dismiss()
                }
            }
        }
    }
    
    override init(nibName nibNameOrNil: String?, bundle nibBundleOrNil: Bundle?) {
        super.init(nibName: nibNameOrNil, bundle: nibBundleOrNil)
        commonInit()
    }
    
    required init?(coder: NSCoder) {
        super.init(coder: coder)
        commonInit()
    }
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
        view.backgroundColor = .black
        if let playerViewController = playerViewController {
            view.addSubview(playerViewController.view)
            playerViewController.view.frame = view.bounds
        }
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
    //    private func metadataItem(_ identifier: AVMetadataIdentifier, stringValue value: String) -> AVMutableMetadataItem {
    //        let metadataItem = AVMutableMetadataItem()
    //        metadataItem.value = value as NSString
    //        metadataItem.extendedLanguageTag = "und"
    //        metadataItem.identifier = identifier
    //        return metadataItem
    //    }
    //
    //    private func metadataItemForMediaContentRating(_ rating: String) -> AVMetadataItem {
    //        return metadataItem(.iTunesMetadataContentRating, stringValue: rating)
    //    }
    
    private func loadNewPlayerViewController() {
        let newPlayerViewController = AVPlayerViewController()
        newPlayerViewController.delegate = self
        customInteractiveVideoOverlay.delegate = self
        customInteractiveVideoOverlay.data = self.filterData
        newPlayerViewController.customOverlayViewController = customInteractiveVideoOverlay
        if let oldPlayerViewController = self.playerViewController {
            oldPlayerViewController.removeFromParent()
            oldPlayerViewController.viewIfLoaded?.removeFromSuperview()
        }
        self.playerViewController = newPlayerViewController
        player = AVQueuePlayer()
        addChild(newPlayerViewController)
        if isViewLoaded {
            overlayView.addSubview(UIImageView(image: UIImage(named: "Logo2")?.alpha(0.5)))
            newPlayerViewController.view.addSubview(overlayView)
            view.addSubview(newPlayerViewController.view)
        }
    }
    
    private func loadAvView()
    {
        NotificationCenter.default.addObserver(self,
                                               selector: #selector(playerItemDidReadyToPlay(notification:)),
                                               name: .AVPlayerItemNewAccessLogEntry,
                                               object: self.playerViewController?.player?.currentItem)
        self.loadNewPlayerViewController()
        self.playChannel()
    }
    
    private func playChannel()
    {
        if let url = URL(string: self.channelObject?.url ?? "")
        {
            let asset = AVURLAsset(url: url)
            let queue = DispatchQueue(label: "FP License Acquire")
            asset.resourceLoader.setDelegate(self, queue: queue)
            let   playerItem = AVPlayerItem(asset: asset)
            
            //        playerItem.externalMetadata = [
            //            metadataItemForMediaContentRating(rating),
            //            metadataItem(AVMetadataIdentifier.commonIdentifierTitle, stringValue: title),
            //            metadataItem(.commonIdentifierDescription, stringValue: description)
            //        ]
            playerItem.addObserver(self, forKeyPath: "status", options: NSKeyValueObservingOptions(), context: nil)
            // NotificationCenter.default.addObserver(self, selector: #selector(playbackStalled(_:)), name: .AVPlayerItemPlaybackStalled, object: playerItem)
            self.pendingPlayerItem = playerItem
            //player?.automaticallyWaitsToMinimizeStalling = true
            player?.replaceCurrentItem(with: playerItem)
            if playerViewController != nil && !isPlaybackActive {
                isPlaybackActive = true
            }
        }
    }
    
    func loadAndPlay() {
        
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
                self.loadAvView()
            }
            DispatchQueue.global(qos: .background).async
                {
                    self.makeApiGeoFencing()
                    
            }
        }
        else {
            self.loadAvView()
        }
        
    }
    
    override func viewWillLayoutSubviews() {
        if let playerViewController = playerViewController {
            playerViewController.view.frame = view.bounds
        }
        super.viewWillLayoutSubviews()
    }
    
    private func _dismiss(stopping: Bool = true) -> Bool {
        if let playerViewController = self.playerViewController {
            isPlaybackActive = false
            if let presenting = presentingViewController {
                presenting.dismiss(animated: true, completion: {
                    // done
                    if stopping {
                        playerViewController.player?.rate = 0.0 // stop playback immediately (don't wait for dealloc)
                    }
                    playerViewController.removeFromParent()
                    playerViewController.view.removeFromSuperview()
                    self.playerViewController = nil
                })
                return true
            }
        }
        if let presenting = presentingViewController {
            if presenting.presentedViewController == self {
                presenting.dismiss(animated: true, completion: {
                    
                })
            }
        }
        return false
    }
    
    // MARK: - AVPlayerViewControllerDelegate
    
    func playerViewControllerShouldDismiss(_ playerViewController: AVPlayerViewController) -> Bool {
        return _dismiss()
    }
    
    func playerViewControllerDidEndDismissalTransition(_ playerViewController: AVPlayerViewController) {
        print("")
    }
    
    //    func playerViewController(_ playerViewController: AVPlayerViewController, skipToNextChannel completion: @escaping (Bool) -> Void) {
    //        // Load content for the new channel, update the playerViewController, and call the completion block to indicate success or failure.
    //        if let url = URL(string: "https://devstreaming-cdn.apple.com/videos/streaming/examples/img_bipbop_adv_example_ts/master.m3u8") {
    //            let playerItem = AVPlayerItem(url: url)
    //            playerViewController.player?.replaceCurrentItem(with: playerItem)
    //            completion(true)
    //        }
    //        completion(false)
    //    }
    
    //    func playerViewController(_ playerViewController: AVPlayerViewController, skipToPreviousChannel completion: @escaping (Bool) -> Void) {
    //        // Load content for the new channel, update the playerViewController, and call the completion block to indicate success or failure.
    //        if let url = URL(string: "https://devstreaming-cdn.apple.com/videos/streaming/examples/img_bipbop_adv_example_ts/master.m3u8") {
    //            let playerItem = AVPlayerItem(url: url)
    //            playerViewController.player?.replaceCurrentItem(with: playerItem)
    //            completion(true)
    //        }
    //        completion(false)
    //    }
}

extension VideoPlaybackViewController {
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
                    let errorId = parsedData["errorid"] as! String
                    let errorMsg = parsedData["errormsg"] as! String
                    //print(#function, "License request failed with an error: \(errorMsg) [\(errorId)]")
                } catch let error as NSError {
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
        
        return true
    }
    
    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, didCancel authenticationChallenge: URLAuthenticationChallenge) {
        
    }
    
    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, didCancel loadingRequest: AVAssetResourceLoadingRequest) {
        
    }
    
    
    func resourceLoader(_ resourceLoader: AVAssetResourceLoader, shouldWaitForRenewalOfRequestedResource renewalRequest: AVAssetResourceRenewalRequest) -> Bool {
        
        return true
    }
    // MARK: AVPlayerViewControllerDelegate
    
    //    func playerViewController(_ playerViewController: AVPlayerViewController, willPresent interstitial: AVInterstitialTimeRange) {
    //        // Prevent the user from skipping intersitials.
    //        playerViewController.requiresLinearPlayback = true
    //    }
    //
    //    func playerViewController(_ playerViewController: AVPlayerViewController, didPresent interstitial: AVInterstitialTimeRange) {
    //        /*
    //         Allow the user to navigate anywhere in the video once an interstitial
    //         has been played.
    //         */
    //        playerViewController.requiresLinearPlayback = false
    //    }
}
extension VideoPlaybackViewController:CustomViews
{
    func callAction(_ tag: Int) {
        
        guard let item = self.filterData?[tag] else {return}
        self.channelObject = item
        self.playChannel()
        
    }
}

extension VideoPlaybackViewController
{
    private func setuPTimer()
    {
        timer = Timer.scheduledTimer(timeInterval: 15, target: self, selector: #selector(runTimedCode), userInfo: nil, repeats: true)
    }
    
    @objc func runTimedCode()
    {
        self.makeApiTimer()
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
                handleErrorWithMessage(self.playerViewController?.player?.currentItem?.error?.localizedDescription, error:self.playerViewController?.player?.currentItem?.error)
                showAlert(withMessage: "Due to some technical reason, channel gets interrupted. kindle try again later.") { _ in
                    self.dismiss(animated: true, completion: nil)
                }
            // Player item failed. See error.
            case .unknown:
                debugPrint("unknown")
                handleErrorWithMessage(self.playerViewController?.player?.currentItem?.error?.localizedDescription, error:self.playerViewController?.player?.currentItem?.error)
            // Player item is not yet ready.
                showAlert(withMessage: "Due to some technical reason, channel gets interrupted. kindle try again later.") { _ in
                    self.dismiss(animated: true, completion: nil)
                }
            @unknown default:
                debugPrint("hello")
            }
        }
    }
    
    func handleErrorWithMessage(_ message: String?, error: Error? = nil) {
        
        if error != nil{
            self.makeApiError(message ?? "", "\((error as NSError?)?.code ?? 0)")
        }
    }
    
    func showAlertWithMessage(_ message: String) {
        DispatchQueue.main.async {
            let alert = UIAlertController(title: appName, message: message, preferredStyle: .actionSheet)
            let okAction = UIAlertAction(title: "Continue", style: .default, handler:{ (action: UIAlertAction!) in
                self.loadAvView()
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
        DispatchQueue.global(qos: .background).async
            {
                self.viewModel.postgaError(self.channelObject?.CallSign ?? "", self.isAllowedZipCode ?? false,errorMsg,errorCode)
        }
    }
}
