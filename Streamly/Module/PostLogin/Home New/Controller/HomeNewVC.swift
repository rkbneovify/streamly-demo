//
//  HomeNewVC.swift
//  Streamly
//
//  Created by Rohit Singh on 7/3/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import TVUIKit



class HomeNewVC: UIViewController {
    
    // MARK: - Properties
    var channelList:EPGResponse?
    {
        didSet {
            viewModel.isPageExist = (channelList?.data?.data?.count ?? 0) > 0
            viewModel.currentPage += 1
            showLoading = false
        }
    }
    var lastIndexPath: IndexPath?
    var genrsList: ChannelListResponse?
    var geoData:GeoFenceAddress?
    var recentData: RecentData?
    var myChannelListData: MychannelData?
    var filteredList:[ChannelDetails]?
    {
        willSet
        {
            currentTimeLabel.text = formatter.string(from: Date())
            hideOrShowLiveObject(shouldHidden: true)
            if (newValue?.count ?? 0) > 0
            {
                self.hideOrShowLiveObject(shouldHidden: false)
            }
        }
        didSet
        {
            self.setupEPGLayout()
        }
    }
    var showLoading = false
    var videoPlaybackViewController: VideoPlaybackViewController

    override init(nibName nibNameOrNil: String?, bundle nibBundleOrNil: Bundle?) {
        videoPlaybackViewController = VideoPlaybackViewController()
        super.init(nibName: nibNameOrNil, bundle: nibBundleOrNil)
    }

    required init?(coder: NSCoder) {
        videoPlaybackViewController = VideoPlaybackViewController()
        super.init(coder: coder)
    }
    var selectedCategories = -1
    let fixedCategoriesArray = ["ALL","RECENT","MY CHANNELS"]
    var timer:Timer?
    var lastContentOffset: CGPoint = CGPoint.zero
    let currentView = UIView()
    let currentTimeLabel = UILabel(frame: CGRect.zero)
    let spinnerImageView = UIImageView(frame: CGRect.zero)
    var formatter = DateFormatter()
    var captureImageView = UIImageView(frame: CGRect.zero)
    let group = DispatchGroup()
    lazy var viewModel: EPGViewModel = {
        return EPGViewModel()
    }()
    
    lazy var liveButton : UIButton =
        {
            let button = UIButton(type: UIButton.ButtonType.system)
            button.frame = CGRect(x: 36, y: 190, width: 170, height: 40)
            button.setTitle("LIVE", for: .normal)
            button.setTitleColor(UIColor.white, for: .normal)
            button.titleLabel?.font = UIFont(name: "HelveticaNeue-Medium", size: 18)
            button.setBackgroundImage(UIImage.imageWithColor(.red), for: .normal)
            button.setBackgroundImage(UIImage.imageWithColor(.red), for: .focused)
            button.setBackgroundImage(UIImage.imageWithColor(.red), for: .highlighted)
            button.layer.cornerRadius = 8
            button.clipsToBounds = true
            button.addTarget(self, action: #selector(liveButtonClicked), for: .primaryActionTriggered)
            return button
    }()

    lazy var headerTimeLbl:UILabel =
        {
            var frame = CGRect(x: 36, y: 240, width: 297, height: 50)
            let label = UILabel(frame: frame)
            label.font = UIFont(name: "HelveticaNeue-Medium", size: 20)
            label.textColor = UIColor.white
            label.backgroundColor = UIColor(patternImage: getSnappshot(withRect: CGRect(x: 0, y: 249, width: 297, height: 58)))
            return label

    }()
    
    override var preferredFocusEnvironments: [UIFocusEnvironment] {
        return [self.cateGiriesCollectionView]
    }
    // MARK: - Outlets
    @IBOutlet weak var collectionView:UICollectionView!
        {
        didSet
        {
            collectionView.dataSource = self
            collectionView.delegate = self
            collectionView.isDirectionalLockEnabled = true
            self.collectionView.alwaysBounceHorizontal = true
            collectionView.register(UINib(nibName: "ProgramCollectionViewCell", bundle: nil),
                                    forCellWithReuseIdentifier: "programCell")
            collectionView.register(UINib(nibName: "ChannelCollectionViewCell", bundle: nil),
                                    forCellWithReuseIdentifier: "channelCell")
            collectionView.register(UINib(nibName: "TimeCollectionViewCell", bundle: nil),
                                    forCellWithReuseIdentifier: "timeCell")
            collectionView.reloadData()
        }
    }
    @IBOutlet weak var cateGiriesCollectionView:UICollectionView!
    @IBOutlet weak var searchButton:          UIButton!
    //    @IBOutlet weak var allButton:             UIButton!
    //    @IBOutlet weak var recentButton:          UIButton!
    //    @IBOutlet weak var myChannelButton:       UIButton!
    //    @IBOutlet weak var sportsButton:          UIButton!
    //    @IBOutlet weak var newsButton:            UIButton!
    //    @IBOutlet weak var moviesButton:          UIButton!
    //    @IBOutlet weak var kidsButton:            UIButton!
    @IBOutlet weak var emptyChanelLabel: UILabel!
    @IBOutlet weak var settingsButton:        UIButton!
    @IBOutlet weak var descriptionLbl:        UILabel!
    
    @IBOutlet weak var discriptionStartTimeLbl: UILabel!
    @IBOutlet weak var discriptionHeader: UILabel!
    @IBOutlet weak var discriptionThumbNail: UIImageView!
    @IBOutlet weak var discriptionView: UIView!

    // MARK: - LifeCycle
    override func viewDidLoad() 
    {
        //self.allButton.backgroundColor = UIColor.firstGradientColor
        super.viewDidLoad()
        self.intialSetup()
        // Do any additional setup after loading the view.
    }
    
    override func viewWillLayoutSubviews() {
        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
    }

    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(animated)
        appDelegate?.getDrmKey()
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        DispatchQueue.global(qos: .background).async
            {
                self.makeApiGeoFencing()
                
        }
    }
    
    override func viewDidDisappear(_ animated: Bool) {
        super.viewDidDisappear(animated)
        invalidateTimer()
    }

    override func viewDidLayoutSubviews() {
        super.viewDidLayoutSubviews()
        //self.collectionView.setContentOffset(self.getCurrenOffset(), animated: false)

    }
    
    // MARK: - Helper
    private func intialSetup()
    {

        // Layout for categories
        let layout: UICollectionViewFlowLayout = UICollectionViewFlowLayout()
        layout.itemSize = CGSize(width: cateGiriesCollectionView.frame.width/6, height: cateGiriesCollectionView.frame.height)
        layout.scrollDirection = .horizontal
        layout.minimumInteritemSpacing = 10
        layout.minimumLineSpacing = 10
        self.cateGiriesCollectionView.collectionViewLayout = layout
        self.collectionView.isScrollEnabled = false
        self.discriptionView.isHidden = true
        formatter.dateFormat = "hh:mm a"
        collectionView.contentInsetAdjustmentBehavior = .never
        if let layout = collectionView?.collectionViewLayout as? CustomCollectionViewLayout
        {
            layout.delegate = self
        }
        currentView.frame = CGRect(x: 671, y: 230, width: 1.5, height: self.view.frame.size.height)
        currentView.backgroundColor = UIColor.white
        self.view.addSubview(currentView)
        self.currentView.isHidden  = true
        
        
        spinnerImageView.image = UIImage(systemName: "smallcircle.fill.circle")
        spinnerImageView.backgroundColor = .clear  // Set background color to see if label is centered
        spinnerImageView.translatesAutoresizingMaskIntoConstraints = false
        self.view.addSubview(spinnerImageView)
        let widthConstraint = NSLayoutConstraint(item: spinnerImageView, attribute: .width, relatedBy: .equal,
                                                 toItem: nil, attribute: .notAnAttribute, multiplier: 1.0, constant: 36)
        let heightConstraint = NSLayoutConstraint(item: spinnerImageView, attribute: .height, relatedBy: .equal,
                                                  toItem: nil, attribute: .notAnAttribute, multiplier: 1.0, constant: 36)
        let xConstraint = NSLayoutConstraint(item: spinnerImageView, attribute: .centerX, relatedBy: .equal, toItem: self.currentView, attribute: .centerX, multiplier: 1, constant: 0)
        let verticalConstraint = NSLayoutConstraint(item: spinnerImageView, attribute: .bottom, relatedBy: .equal, toItem: currentView, attribute: .top, multiplier: 1, constant: 0)
        NSLayoutConstraint.activate([widthConstraint, heightConstraint, xConstraint, verticalConstraint])
        
        currentTimeLabel.backgroundColor = .clear
        currentTimeLabel.font = UIFont(name: "HelveticaNeue-Light", size: 18)
        currentTimeLabel.textColor = UIColor.white
        currentTimeLabel.translatesAutoresizingMaskIntoConstraints = false
        self.view.addSubview(currentTimeLabel)
        currentTimeLabel.isHidden = true
        let labelWidthConstraint = NSLayoutConstraint(item: currentTimeLabel, attribute: .width, relatedBy: .equal, toItem: nil, attribute: .notAnAttribute, multiplier: 1.0, constant: 80)
        let labelHeightConstraint = NSLayoutConstraint(item: currentTimeLabel, attribute: .height, relatedBy: .equal,toItem: nil, attribute: .notAnAttribute, multiplier: 1.0, constant: 75)
        let leadingConstraint = NSLayoutConstraint(item: currentTimeLabel, attribute: .leading, relatedBy: .equal, toItem: self.spinnerImageView, attribute: .trailing, multiplier: 1, constant: 8)
        let yConstraint = NSLayoutConstraint(item: currentTimeLabel, attribute: .centerY, relatedBy: .equal, toItem: spinnerImageView, attribute: .centerY, multiplier: 1, constant: 0)
        NSLayoutConstraint.activate([labelWidthConstraint, labelHeightConstraint, leadingConstraint, yConstraint])
        
        self.view.addSubview(liveButton)
        self.view.addSubview(headerTimeLbl)
        //   self.view.bringSubviewToFront(self.headerTimeLbl)
        
        
        captureImageView.frame = CGRect(x: 0, y: 0, width: collectionView.frame.size.width, height: 58)
        captureImageView.layer.zPosition = 1019
        captureImageView.tag = 1019
        self.collectionView.addSubview(captureImageView)
        captureImageView.image = getSnappshot(withRect: CGRect(x: 36, y: 249, width: collectionView.frame.size.width, height: 58))
        
        hideOrShowLiveObject(shouldHidden: true)
        
        self.makeApiCall()
        self.initVM()
        DispatchQueue.global(qos: .background).async
            {
                self.makeApiCallChannelList()
                self.initVMList()
        }
        DispatchQueue.global(qos: .background).async
            {
                self.makeApiCallRecent()
                self.initVMRecent()
        }

        group.notify(queue: .main)
        {
            self.removeSpinner()
        }
    }
    
    
    private func hideOrShowLiveObject(shouldHidden : Bool)
    {
        currentView.isHidden = shouldHidden
        spinnerImageView.isHidden = shouldHidden
        liveButton.isHidden = shouldHidden
        currentTimeLabel.isHidden = true
    }

    private func getSnappshot(withRect : CGRect) -> UIImage
    {
        let y = withRect.minY.pointsToPixel()
        let width = withRect.width.pointsToPixel()
        let height = withRect.height.pointsToPixel()
        let rect = CGRect(x: withRect.origin.x, y: y, width: width, height: height)
        return Utility.cropImage(image: UIImage(named: "bacgroundImage")!, toRect: rect) ?? UIImage()
    }

    private func configureButtons()
    {
        //self.liveButton.layer.borderWidth = 0.0
        //        self.allButton.layer.borderWidth = 0.0
        //        self.recentButton.layer.borderWidth = 0.0
        //        self.myChannelButton.layer.borderWidth = 0.0
        //        self.sportsButton.layer.borderWidth = 0.0
        //        self.newsButton.layer.borderWidth = 0.0
        //        self.moviesButton.layer.borderWidth = 0.0
        //        self.kidsButton.layer.borderWidth = 0.0
        self.settingsButton.layer.borderWidth = 0.0
        self.searchButton.layer.borderWidth = 0.0
        
    }
    
    func getCurrenOffset() -> CGPoint
    {
        var comp: DateComponents = Calendar.current.dateComponents([.year, .month, .day,], from: Date())
        var comp1: DateComponents = Calendar.current.dateComponents([.year, .month, .day,.hour,.minute], from: Date())
        comp1.timeZone = TimeZone(abbreviation: "UTC")!
        comp.timeZone = TimeZone(abbreviation: "UTC")!
        if let d1 = Calendar.current.date(from: comp)?.dayBefore
        {
            let d2 = Calendar.current.date(from: comp1)!
            let intervals = (d2.timeIntervalSince(d1))
            let numberOfHours = CGFloat(intervals / 3600)
            var offset = self.collectionView.contentOffset
            offset.x = (((CGFloat(numberOfHours) * (408*2))) - (297 + 36))
            return offset
        }
        var offset = self.collectionView.contentOffset
        offset.x = 0.0
        return offset


        
    }
    
    private func setupEPGLayout()
    {
        collectionView.collectionViewLayout.invalidateLayout()
        collectionView.reloadData()
        collectionView.layoutIfNeeded()
        startTimer()
        self.collectionView.setContentOffset(self.getCurrenOffset(), animated: false)
    }
    
     func selectCategories(_ sender:Int, key:String? = nil)
    {
        invalidateTimer()
        switch sender {
        case CategoriesSelect.all.rawValue, CategoriesSelect.recent.rawValue, CategoriesSelect.myChannels.rawValue:
            // guard  self.selectedCategories != sender else {return}
            if channelList != nil {
                if CategoriesSelect.init(rawValue: sender) == .recent {
                    self.viewModel.getRecentData()
                    if (filteredList?.count ?? 0) > 0 && viewModel.currentPage > 1 {
                        let tempRecent = self.loadRecentData(self.recentData?.data ?? [])
                        self.filteredList?.append(contentsOf: tempRecent ?? [])
                    } else {
                        self.filteredList?.removeAll()
                        self.filteredList = self.loadRecentData(self.recentData?.data ?? [])
                    }
                    if (filteredList?.count ?? 0) <= (self.recentData?.data?.count ?? 0) { // This works only when data have in Recent and we didn't get all Recent data in once.
                        group.enter()
                        self.viewModel.getEPGData(viewModel.currentPage)
                        viewModel.isPageExist = true
                        return
                    }
                    if viewModel.isPageExist {
                        if let indexPath = self.lastIndexPath {
                            if viewModel.currentPage > 1 && (filteredList?.count ?? 0) > 0 {
                                collectionView.scrollToItem(at: indexPath, at: .centeredHorizontally, animated: true)
                            }
                        }
                    }
                } else if CategoriesSelect.init(rawValue: sender) == .myChannels {
                    self.viewModel.getMyChannelList()
                    if (filteredList?.count ?? 0) > 0 && viewModel.currentPage > 1 {
                        let tempRecent = self.loadMyChannelData(self.myChannelListData?.data ?? [])
                        self.filteredList?.append(contentsOf: tempRecent ?? [])
                    } else {
                        self.filteredList?.removeAll()
                        self.filteredList = self.loadMyChannelData(self.myChannelListData?.data ?? [])
                    }
                    if (filteredList?.count ?? 0) <= (self.myChannelListData?.data?.count ?? 0) { // This works only when data have in favourites and we didn't get all favourite data in once.
                        group.enter()
                        self.viewModel.getEPGData(viewModel.currentPage)
                        viewModel.isPageExist = true
                        return
                    }
                    if viewModel.isPageExist {
                        if let indexPath = self.lastIndexPath {
                            if viewModel.currentPage > 1 && (filteredList?.count ?? 0) > 0 {
                                collectionView.scrollToItem(at: indexPath, at: .centeredHorizontally, animated: true)
                            }
                        }
                    }
                }
                    else {
                    if (filteredList?.count ?? 0) > 0 && viewModel.currentPage > 1 {
                        self.filteredList?.append(contentsOf: self.channelList?.data?.data ?? [])
                    } else {
                        self.filteredList?.removeAll()
                        self.filteredList = self.channelList?.data?.data ?? []
                    }
                    if viewModel.isPageExist {
                        if let indexPath = self.lastIndexPath {
                            if viewModel.currentPage > 1 && (filteredList?.count ?? 0) > 0 {
                                collectionView.scrollToItem(at: indexPath, at: .centeredHorizontally, animated: true)
                            }
                        }
                    }
                }
            } else {
                viewModel.isPageExist = true
                viewModel.lastPage = 10
                lastIndexPath = nil
                filteredList?.removeAll()
                self.showSpinner(onView: self.view)
                group.enter()
                viewModel.currentPage = 1
                viewModel.getEPGData(1)
            }
//            collectionView.reloadData()
            self.selectedCategories = sender
//        case  CategoriesSelect.recent.rawValue:
//            // guard  self.recentData?.data?.count ?? 0 > 0 else {return self.showSimpleAlertWithMessage("No Recent data available!")}
//           // guard  self.selectedCategories != sender else {return}
//            self.filteredList?.removeAll()
//
//            self.selectedCategories = sender
//            self.collectionView.reloadData()
//        case  CategoriesSelect.myChannels.rawValue:
//            // guard  self.selectedCategories != sender else {return}
//            self.selectedCategories = sender
//            debugPrint("myChannels")
        default:
            self.selectedCategories = sender
            if channelList != nil {
                if (filteredList?.count ?? 0) > 0 && viewModel.currentPage > 1 {
                    self.filteredList?.append(contentsOf: self.channelList?.data?.data ?? [])
                } else {
                    self.filteredList?.removeAll()
                    self.filteredList = self.channelList?.data?.data ?? []
                }
                if viewModel.isPageExist {
                    if let indexPath = self.lastIndexPath {
                        if viewModel.currentPage > 1 && (filteredList?.count ?? 0) > 0 {
                            collectionView.scrollToItem(at: indexPath, at: .centeredHorizontally, animated: true)
                        }
                    }
                }
            } else {
                viewModel.isPageExist = true
                viewModel.lastPage = 10
                viewModel.currentPage = 1
                filteredList?.removeAll()
                self.lastIndexPath = nil
                self.showSpinner(onView: self.view)
                viewModel.getEPGData(1, key ?? "")
            }
            // guard  self.selectedCategories != sender else {return}
//            self.filteredList?.removeAll()
//            self.filteredList = self.loadData(self.genrsList?.data?[[key ?? ""][0]] ?? [])
//            self.selectedCategories = sender
//            self.collectionView.reloadData()
        }
        self.collectionView.reloadData()
        self.cateGiriesCollectionView.reloadData()
//        var offset = self.cateGiriesCollectionView.contentOffset
//        offset.x = CGFloat(130 * sender)
//        self.cateGiriesCollectionView.setContentOffset(offset, animated: true)

    }
    
    
    private func loadData(_ categories:[ChannelList]) -> [ChannelDetails]?
    {
        let mainArray = self.channelList?.data?.data?.filter { i in (categories.contains{ i.CallSign == $0.callSign } ) }
        return mainArray
        
    }
    
    private func loadMyChannelData(_ myChannelList:[MyChannel]) -> [ChannelDetails]?
    {
        let mainArray = self.channelList?.data?.data?.filter { i in (myChannelList.contains{ i.id == $0.id} ) }
        return mainArray
        
    }
    
    private func loadRecentData(_ categories:[RecentDetails]) -> [ChannelDetails]?
    {
        let mainArray = self.channelList?.data?.data?.filter { i in (categories.contains{ i.CallSign == $0.callSign } ) }
        return mainArray
        
    }
    
    private func makeApiCall(){
        group.enter()
//        self.showSpinner(onView: self.view)
        viewModel.delegate = self
        viewModel.getEPGData()
        
    }
    
    private func initVM() {
        viewModel.epgData = { epgModel in
            self.group.notify(queue: .main) {
                self.group.leave()
                self.removeSpinner()
            }
            self.removeSpinner()
            self.channelList = epgModel
            if self.selectedCategories == -1 {
                self.selectCategories(0)
            } else {
                self.selectCategories(self.selectedCategories)
            }
        }
    }
    
    private func makeApiCallChannelList(){
        group.enter()
        viewModel.getChannelList()
//        viewModel.getMyChannelList()
    }
    
    private func initVMList() {
        
        viewModel.channelData = { epgModel in
            self.group.leave()
            
            self.genrsList = epgModel
            self.cateGiriesCollectionView.reloadData()
        }
        
    }
    
    private func makeApiCallRecent(){
        group.enter()
        viewModel.getRecentData()
        
    }
    
    private func makeApiGeoFencing(){
        viewModel.postAddressData()
        initVMGeoData()
        
    }
    
    private func initVMRecent() {
        
        viewModel.recentData = { recentModel in
            self.group.leave()
            self.recentData = recentModel
        }
        
        viewModel.myChannelList = { [weak self] data in
            guard let weak = self else { return }
            weak.myChannelListData = data
        }
        
    }
    
    private func initVMGeoData() {
        
        viewModel.geoData = { geoDataModel in
            
            self.geoData = geoDataModel
            
        }
        
    }
    
    // MARK: - IBActions
    
    @IBAction private func liveButtonClicked()
    {
        if !(timer?.isValid ?? false)
        {
            let currentTime = formatter.string(from: Date())
            currentTimeLabel.text = currentTime
            setLiveBtnView(UIColor.red,tittle: "LIVE")
            currentView.frame = CGRect(x: 671, y: 230, width: 1.5, height: self.view.frame.size.height)
            startTimer()
            self.collectionView.setContentOffset(self.getCurrenOffset(), animated: true)
            hideOrShowLiveObject(shouldHidden: false)
        }
    }
    
    @IBAction func searchAction(_ sender:UIButton){
        
        let viewVC =  SearchVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        let searchController = UISearchController(searchResultsController: viewVC)
        searchController.searchResultsUpdater = viewVC
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Channels)", comment: "")
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")

        guard let rootViewController = view.window?.rootViewController else { fatalError("Unable to get root view controller.") }
//        viewVC.channelList = self.channelList
        viewVC.geoData = self.geoData
        // searchController.view.backgroundColor = UIColor.li
        present(searchContainer, animated: true, completion: nil)
    }
    
    @IBAction func settingsAction(_ sender:UIButton){
        
        let settingVc = SettingsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        self.navigationController?.pushViewController(settingVc, animated: true)
    }
    
    @IBAction func categoriesAction(_ sender:UIButton) {
        
        // self.selectCategories(sender.tag)
        
        
    }
    
}

extension HomeNewVC:HandleError {
    func successAPI() {
        self.removeSpinner()
    }
    
    
    func handleError(_ message: String, _ statusCode: Int) {
        self.removeSpinner()
        if statusCode == 401 { // Session Expired
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
        } else if statusCode == 402 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
            return
        } else if statusCode == 403 {
            showAlert(withMessage: message, setFirstButtonTitle: "OK", handler: {
                _ in
            })
           return
        } else if statusCode == 404 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
            return
        }
        //self.showSimpleAlertWithMessage(message)
    }
    
}

extension HomeNewVC{
    override func didUpdateFocus(in context: UIFocusUpdateContext, with coordinator: UIFocusAnimationCoordinator) {
        if settingsButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.settingsButton.layer.borderWidth = 3.0
                self.settingsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
        else if searchButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.searchButton.layer.borderWidth = 3.0
                self.searchButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
            //        else if allButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.allButton.layer.borderWidth = 3.0
            //                self.allButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }
            //        else if recentButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.recentButton.layer.borderWidth = 3.0
            //                self.recentButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }
            //        else if myChannelButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.myChannelButton.layer.borderWidth = 3.0
            //                self.myChannelButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }
            //
            //        else if sportsButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.sportsButton.layer.borderWidth = 3.0
            //                self.sportsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }
            //        else if newsButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.newsButton.layer.borderWidth = 3.0
            //                self.newsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }
            //        else if moviesButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.moviesButton.layer.borderWidth = 3.0
            //                self.moviesButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }
            //        else if kidsButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.kidsButton.layer.borderWidth = 3.0
            //                self.kidsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }

       else if collectionView.isFocused {
            if let nextCell = context.nextFocusedView as? ProgramCollectionViewCell {
                let index = Int(nextCell.accessibilityHint!)!
                DispatchQueue.main.asyncAfter(deadline: .now() + 0.3) { // 2
                    self.collectionView.scrollToItem(at: IndexPath(item: index, section: 0), at: .centeredVertically, animated: true)
                }
            }
        } else {
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
            }, completion: nil)
        }
    }
}

extension HomeNewVC:AddRecentData
{
    func addRecentData(_ data: RecentData) {
        self.recentData = data
    }


}

extension HomeNewVC :GetChannelData
{
    func getProgramAt(channelIndex: Int) -> (channelName: String?, programArray: [ProgramArray]?)
    {
        return (filteredList?[channelIndex - 1].StationName, filteredList?[channelIndex - 1].programs)
    }
}

extension HomeNewVC:UIScrollViewDelegate
{
    func scrollViewWillBeginDragging(_ scrollView: UIScrollView)
    {
        if scrollView == self.collectionView
        {
            invalidateTimer()
        }
    }
    func scrollViewDidScroll(_ scrollView: UIScrollView)
    {
        if self.collectionView == scrollView
        {
            let scrollViewOffset = scrollView.contentOffset
            if !(timer?.isValid ?? false)
            {
                if (self.lastContentOffset.x < scrollView.contentOffset.x)
                {
                    let frame = (CGRect(x: currentView.frame.origin.x+(lastContentOffset.x - scrollView.contentOffset.x), y: currentView.frame.origin.y, width: currentView.frame.size.width, height: currentView.frame.size.height))
                    currentView.frame = frame

                }
                else if (self.lastContentOffset.x > scrollView.contentOffset.x)
                {
                    let frame = (CGRect(x: currentView.frame.origin.x-(scrollView.contentOffset.x - lastContentOffset.x), y: currentView.frame.origin.y, width: currentView.frame.size.width, height: currentView.frame.size.height))
                    currentView.frame = frame
                }
                if scrollView.contentOffset.x >= (297 + getCurrenOffset().x)
                {
                    currentTimeLabel.isHidden = true
                    spinnerImageView.isHidden = true
                    currentView.isHidden = true
                }
                else
                {
                    currentTimeLabel.isHidden = true
                    spinnerImageView.isHidden = false
                    currentView.isHidden = false
                }
            }
            self.lastContentOffset = scrollViewOffset
        }
    }
}
