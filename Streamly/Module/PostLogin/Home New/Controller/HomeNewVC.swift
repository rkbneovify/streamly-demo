//
//  HomeNewVC.swift
//  Streamly
//
//  Created by Rohit Singh on 7/3/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class HomeNewVC: UIViewController {
    
    // MARK: - Properties
    var channelList:EPGResponse?
    {
        didSet {
            self.selectCategories(11)
        }
    }
    var genrsList: ChannelListResponse?
    var geoData:GeoFenceAddress?
    var recentData: RecentData?
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
    var selectedCategories = 0
    var recentIDs:[Int] = []
    var timer:Timer?
    var lastContentOffset: CGPoint = CGPoint.zero
    let currentView = UIView()
    let currentTimeLabel = UILabel(frame: CGRect.zero)
    let spinnerImageView = UIImageView(frame: CGRect.zero)
    var formatter = DateFormatter()
    var captureImageView = UIImageView(frame: CGRect.zero)
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
    
    override var preferredFocusedView: UIView? {
        get {
            return self.allButton
        }
    }
    
    // MARK: - Outlets
    @IBOutlet weak var collectionView:UICollectionView!
        {
        didSet
        {
            collectionView.dataSource = self
            collectionView.delegate = self
            collectionView.isDirectionalLockEnabled = true
            collectionView.register(UINib(nibName: "ProgramCollectionViewCell", bundle: nil),
                                    forCellWithReuseIdentifier: "programCell")
            collectionView.register(UINib(nibName: "ChannelCollectionViewCell", bundle: nil),
                                    forCellWithReuseIdentifier: "channelCell")
            collectionView.register(UINib(nibName: "TimeCollectionViewCell", bundle: nil),
                                    forCellWithReuseIdentifier: "timeCell")
        }
    }
    @IBOutlet weak var searchButton:          UIButton!
    @IBOutlet weak var allButton:             UIButton!
    @IBOutlet weak var recentButton:          UIButton!
    @IBOutlet weak var myChannelButton:       UIButton!
    @IBOutlet weak var sportsButton:          UIButton!
    @IBOutlet weak var newsButton:            UIButton!
    @IBOutlet weak var moviesButton:          UIButton!
    @IBOutlet weak var kidsButton:            UIButton!
    @IBOutlet weak var settingsButton:        UIButton!
    
    // MARK: - LifeCycle
    override func viewDidLoad()
    {
        super.viewDidLoad()
        self.intialSetup()
        // Do any additional setup after loading the view.
    }
    
    override func viewWillLayoutSubviews() {
        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
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
    
    // MARK: - Helper
    private func intialSetup()
    {
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
    }
    
    
    private func hideOrShowLiveObject(shouldHidden : Bool)
    {
        currentView.isHidden = shouldHidden
        spinnerImageView.isHidden = shouldHidden
        liveButton.isHidden = shouldHidden
        currentTimeLabel.isHidden = shouldHidden
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
        self.allButton.layer.borderWidth = 0.0
        self.recentButton.layer.borderWidth = 0.0
        self.myChannelButton.layer.borderWidth = 0.0
        self.sportsButton.layer.borderWidth = 0.0
        self.newsButton.layer.borderWidth = 0.0
        self.moviesButton.layer.borderWidth = 0.0
        self.kidsButton.layer.borderWidth = 0.0
        self.settingsButton.layer.borderWidth = 0.0
        self.searchButton.layer.borderWidth = 0.0
        
    }
    
    func getCurrenOffset() -> CGPoint
    {
        var comp: DateComponents = Calendar.current.dateComponents([.year, .month, .day,], from: Date())
        var comp1: DateComponents = Calendar.current.dateComponents([.year, .month, .day,.hour,.minute], from: Date())
        comp1.timeZone = TimeZone(abbreviation: "UTC")!
        comp.timeZone = TimeZone(abbreviation: "UTC")!
        let d1 = Calendar.current.date(from: comp)!
        let d2 = Calendar.current.date(from: comp1)!
        let intervals = (d2.timeIntervalSince(d1))
        let numberOfHours = CGFloat(intervals / 3600)
        var offset = self.collectionView.contentOffset
        offset.x = (((CGFloat(numberOfHours) * (408*2))) - (297 + 36))
        return offset
    }
    
    private func setupEPGLayout()
    {
        collectionView.reloadData()
        startTimer()
        self.collectionView.setContentOffset(self.getCurrenOffset(), animated: false)
    }
    
    private func selectCategories(_ sender:Int)
    {
        invalidateTimer()
        switch sender {
        case CategoriesSelect.all.rawValue:
            guard  self.selectedCategories != CategoriesSelect.all.rawValue else {return}
            self.filteredList = self.channelList?.data
            self.selectedCategories = CategoriesSelect.all.rawValue
        case  CategoriesSelect.recent.rawValue:
            guard  self.recentData?.data?.count ?? 0 > 0 else {return self.showSimpleAlertWithMessage("No Recent data available!")}
            guard  self.selectedCategories != CategoriesSelect.recent.rawValue else {return}
            self.filteredList = self.loadRecentData(self.recentData?.data ?? [])
            self.selectedCategories = CategoriesSelect.recent.rawValue
        case  CategoriesSelect.myChannels.rawValue:
            debugPrint("myChannels")
        case  CategoriesSelect.sports.rawValue:
            guard  self.selectedCategories != CategoriesSelect.sports.rawValue else {return}
            self.filteredList = self.loadData(self.genrsList?.data?.Sports ?? [])
            self.selectedCategories = CategoriesSelect.sports.rawValue
        case  CategoriesSelect.news.rawValue:
            guard  self.selectedCategories != CategoriesSelect.news.rawValue else {return}
            self.filteredList = self.loadData(self.genrsList?.data?.News ?? [])
            self.selectedCategories = CategoriesSelect.news.rawValue
        case  CategoriesSelect.movies.rawValue:
            guard  self.selectedCategories != CategoriesSelect.movies.rawValue else {return}
            self.filteredList = self.loadData(self.genrsList?.data?.Movies ?? [])
            self.selectedCategories = CategoriesSelect.movies.rawValue
        case  CategoriesSelect.kids.rawValue:
            guard  self.selectedCategories != CategoriesSelect.kids.rawValue else {return}
            self.filteredList = self.loadData(self.genrsList?.data?.Kids ?? [])
            self.selectedCategories = CategoriesSelect.kids.rawValue
        default:
            return
            
        }
    }
    
    private func loadData(_ categories:[ChannelList]) -> [ChannelDetails]?
    {
        let mainArray = self.channelList?.data?.filter { i in (categories.contains{ i.CallSign == $0.callSign } ) }
        return mainArray
        
    }
    
    private func loadRecentData(_ categories:[RecentDetails]) -> [ChannelDetails]?
    {
        let mainArray = self.channelList?.data?.filter { i in (categories.contains{ i.CallSign == $0.callSign } ) }
        return mainArray
        
    }
    
    private func makeApiCall(){
        self.showSpinner(onView: self.view)
        viewModel.delegate = self
        viewModel.getEPGData()
        
    }
    
    private func initVM() {
        
        viewModel.epgData = { epgModel in
            self.removeSpinner()
            self.channelList = epgModel
            
        }
    }
    
    private func makeApiCallChannelList(){
        viewModel.getChannelList()
        
    }
    
    private func initVMList() {
        
        viewModel.channelData = { epgModel in
            
            self.genrsList = epgModel
        }
        
    }
    
    private func makeApiCallRecent(){
        viewModel.getRecentData()
        
    }
    
    private func makeApiGeoFencing(){
        viewModel.postAddressData()
        initVMGeoData()
        
    }
    
    private func initVMRecent() {
        
        viewModel.recentData = { recentModel in
            
            self.recentData = recentModel
            self.recentIDs = self.recentData?.data?.map { $0.id ?? 0} ?? []
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
        
        viewVC.channelList = self.channelList
        guard let rootViewController = view.window?.rootViewController else { fatalError("Unable to get root view controller.") }
        viewVC.channelList = self.channelList
        // searchController.view.backgroundColor = UIColor.li
        rootViewController.present(searchController, animated: true, completion: nil)
    }
    
    @IBAction func settingsAction(_ sender:UIButton){
        
        let settingVc = SettingsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        self.navigationController?.pushViewController(settingVc, animated: true)
    }
    
    @IBAction func categoriesAction(_ sender:UIButton) {
        
        self.selectCategories(sender.tag)
        
        
    }
    
}

extension HomeNewVC : UICollectionViewDelegate, UICollectionViewDataSource {
    func numberOfSections(in collectionView: UICollectionView) -> Int
    {
        guard let channels = filteredList, channels.count > 0 else { return 0}
        return (channels.count + 1)
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        guard let data =  filteredList else { return 0}
        return ((section == 0) ? daysData : ((data[section - 1].programs?.count ?? 0) + 1))
    }


    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        if indexPath.section == 0
        {
            let timeCell = cell as? TimeCollectionViewCell
            timeCell?.configureCell(indexInt: indexPath.row)
            timeCell?.layer.zPosition = 2000
            timeCell?.backgroundColor = .clear

        }
        else {

            if indexPath.row == 0
            {
                let channelCell = cell as? ChannelCollectionViewCell
                if  let  channel = filteredList?[indexPath.section - 1]
                {
                    channelCell?.configureWith(channel: channel)
                }
            }
            else {
                let programCell = cell as? ProgramCollectionViewCell

                if let channel = filteredList?[indexPath.section - 1], let programs = channel.programs
                {
                    self.headerTimeLbl.text = Utility.getDateFromTimeStampByFormat(from: filteredList?[indexPath.section - 1].programs?[indexPath.item - 1].start ?? 0.0, "E, d MMM yyyy")
                    programCell?.configureWithModel(program: programs[indexPath.item - 1], row : indexPath.row)
                }
            }

        }
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        if indexPath.section == 0
        {
            guard let timeCell = collectionView.dequeueReusableCell(withReuseIdentifier: "timeCell", for: indexPath) as? TimeCollectionViewCell else {return UICollectionViewCell()}
            return timeCell
        }
        else {
            
            if indexPath.row == 0
            {
                guard let channelCell = collectionView.dequeueReusableCell(withReuseIdentifier: "channelCell", for: indexPath) as? ChannelCollectionViewCell else {return UICollectionViewCell()}

                return channelCell
            }
            else {
                guard let programCell = collectionView.dequeueReusableCell(withReuseIdentifier: "programCell", for: indexPath) as? ProgramCollectionViewCell else {return UICollectionViewCell()}

                return programCell
            }
            
        }
        
    }
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        debugPrint("section = " + "\(indexPath.section), " + "row = " + "\(indexPath.row)")
        if indexPath.section != 0
        {
            guard let item = filteredList?[(indexPath.section - 1)] else {return}
            let viewController = PlayerVC.instantiateFromAppStoryboard(appStoryboard: .Main)
            guard let url = URL(string: "https://bitdash-a.akamaihd.net/content/sintel/hls/playlist.m3u8") else {return}
            viewController.url = url
            self.present(viewController, animated: true, completion: nil)
        }
    }
}

extension HomeNewVC:HandleError
{
    func handleError(_ message: String) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!")
    }
    
}

// MARK:  Timer

extension HomeNewVC {
    func startTimer()
    {
        timer = Timer.scheduledTimer(withTimeInterval: 5, repeats: true) { timer in
            self.getCurrentTime()
        }
    }
    
    func invalidateTimer()
    {
        if let _ = timer
        {
            timer!.invalidate()
            timer = nil
            setLiveBtnView(UIColor.lightGray,tittle: "GO LIVE")

        }
    }

    private func setLiveBtnView(_ color:UIColor , tittle:String)
    {
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .normal)
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .focused)
        self.liveButton.setBackgroundImage(UIImage.imageWithColor(color), for: .highlighted)
        liveButton.setTitle(tittle, for: .normal)
    }
    
    func getCurrentTime()
    {
        let currentTime = formatter.string(from: Date())
        if currentTimeLabel.text != currentTime
        {
            currentTimeLabel.text = currentTime
            var offset = self.collectionView.contentOffset
            offset.x += 13.5
            self.collectionView.setContentOffset(offset, animated: true)
        }
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
        else if allButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.allButton.layer.borderWidth = 3.0
                self.allButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
        else if recentButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.recentButton.layer.borderWidth = 3.0
                self.recentButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
        else if myChannelButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.myChannelButton.layer.borderWidth = 3.0
                self.myChannelButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
            
        else if sportsButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.sportsButton.layer.borderWidth = 3.0
                self.sportsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
        else if newsButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.newsButton.layer.borderWidth = 3.0
                self.newsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
        else if moviesButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.moviesButton.layer.borderWidth = 3.0
                self.moviesButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
        else if kidsButton.isFocused{
            coordinator.addCoordinatedAnimations({ () -> Void in
                self.configureButtons()
                self.kidsButton.layer.borderWidth = 3.0
                self.kidsButton.layer.borderColor = UIColor.secondGradientColor.cgColor
                
                
            }, completion: nil)
        }
            //        else if liveButton.isFocused{
            //            coordinator.addCoordinatedAnimations({ () -> Void in
            //                self.configureButtons()
            //                self.liveButton.layer.borderWidth = 3.0
            //                self.liveButton.layer.borderColor = UIColor.secondGradientColor.cgColor
            //
            //
            //            }, completion: nil)
            //        }
            
            
            
        else {
            self.configureButtons()
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
        invalidateTimer()
    }
    func scrollViewDidScroll(_ scrollView: UIScrollView)
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
                currentTimeLabel.isHidden = false
                spinnerImageView.isHidden = false
                currentView.isHidden = false
            }
        }
        self.lastContentOffset = scrollViewOffset
    }
}

