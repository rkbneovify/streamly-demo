//
//  HomeVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/15/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

import AVKit

class HomeVC: UIViewController {
    
    //MARK: Outlets
    @IBOutlet weak var collectionView:        UICollectionView!
    @IBOutlet weak var searchButton:          UIButton!
    @IBOutlet weak var allButton:             UIButton!
    @IBOutlet weak var recentButton:          UIButton!
    @IBOutlet weak var myChannelButton:       UIButton!
    @IBOutlet weak var sportsButton:          UIButton!
    @IBOutlet weak var newsButton:            UIButton!
    @IBOutlet weak var moviesButton:          UIButton!
    @IBOutlet weak var kidsButton:            UIButton!
    @IBOutlet weak var goBackButton:          UIButton!
    @IBOutlet weak var settingsButton:        UIButton!
    
    //MARK: Properties
    var channels: EPGResponse?
    {
        didSet{
            self.loadData()
        }
    }
    var channelsList: ChannelListResponse?
    var channels1:[Channel]?
    {
        didSet
        {
            //self.removeSpinner()
        }
    }
    lazy var viewModel: EPGViewModel = {
        return EPGViewModel()
    }()
    var timer: Timer?
    var categoriesFlag = false
    var timeIntervals: [String] {
        return [
            "ALL CHANNELS", "00:00", "01:00", "02:00", "03:00", "04:00", "05:00", "06:00", "07:00", "08:00", "09:00", "10:00", "11:00", "12:00",
            "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00"
        ]
    }
    override var preferredFocusedView: UIView? {
        get {
            return self.allButton
        }
    }
    
    //MARK: LifeCycle Methods
    override func viewDidLoad() {
        super.viewDidLoad()
        self.configureButtons()
        self.initialSetup()
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        startTimer()
    }
    
    override func viewDidDisappear(_ animated: Bool) {
        super.viewDidDisappear(animated)
        invalidateTimer()
    }
    
    override func viewDidLayoutSubviews() {
        super.viewDidLayoutSubviews()
        
    }
    
    //MARK: Helpers
    
    private func initialSetup()
    {
        self.makeApiCall()
        self.initVM()
        DispatchQueue.global(qos: .background).async
            {
                self.makeApiCallChannelList()
                self.initVMList()
        }
        //
        let epgNib = UINib(nibName: "EPGCollectionViewCell", bundle: Bundle.main)
        collectionView.register(epgNib, forCellWithReuseIdentifier: "epgCell")
        
        let timeNib = UINib(nibName: "TimeCollectionViewCell", bundle: Bundle.main)
        collectionView.register(timeNib, forCellWithReuseIdentifier: "timeCell")
        
        let channelNib = UINib(nibName: "ChannelCollectionViewCell", bundle: Bundle.main)
        collectionView.register(channelNib, forCellWithReuseIdentifier: "channelCell")
        
        let programNib = UINib(nibName: "ProgramCollectionViewCell", bundle: Bundle.main)
        collectionView.register(programNib, forCellWithReuseIdentifier: "programCell")
//        let layout = collectionView.collectionViewLayout as? UICollectionViewFlowLayout // casting is required because UICollectionViewLayout doesn't offer header pin. Its feature of UICollectionViewFlowLayout
//        layout?.sectionHeadersPinToVisibleBounds = true


        
        // self.loadData()

        //   self.setCollectionViewData()
        collectionView.backgroundColor = UIColor.clear
    }

    private func setCollectionViewData()
    {
        self.removeSpinner()
        let layout = EPGCollectionViewLayout()
       // layout.sc
        layout.channels = self.channels1
        collectionView.setCollectionViewLayout(layout, animated: true)
        collectionView.dataSource = self
        collectionView.delegate = self
        self.collectionView.reloadData()
        self.collectionView.scrollToItem(at: IndexPath(row: 11, section: 0), at: .left, animated: false)
       // self.collectionVi = true
        collectionView.layoutIfNeeded()
        collectionView.layoutSubviews()
    }

    func configureButtons()
    {
        if let image = UIImage(systemName: "magnifyingglass")
        {
            self.searchButton.setBackgroundImage(image, for: .normal)
            self.searchButton.setBackgroundImage(image, for: .focused)
            self.searchButton.setBackgroundImage(image, for: .highlighted)
        }
        
        self.allButton.setBtnProperties(self.allButton)
        self.recentButton.setBtnProperties(self.recentButton)
        self.myChannelButton.setBtnProperties(self.myChannelButton)
        self.sportsButton.setBtnProperties(self.sportsButton)
        self.newsButton.setBtnProperties(self.newsButton)
        self.moviesButton.setBtnProperties(self.moviesButton)
        self.kidsButton.setBtnProperties(self.kidsButton)
        self.settingsButton.setBtnProperties(self.settingsButton)
        
    }

    private func makeApiCall(){
        self.showSpinner(onView: self.view)
        viewModel.delegate = self
        viewModel.getEPGData()

    }

    private func initVM() {

        viewModel.epgData = { epgModel in
        self.removeSpinner()
            self.channels = epgModel

        }

    }

    private func makeApiCallChannelList(){
        viewModel.getChannelList()
        
    }
    
    private func initVMList() {
        
        viewModel.channelData = { epgModel in
            
            self.channelsList = epgModel


            
        }
        
    }

    
    //MARK: Properties
    
    func loadData() {
        //self.showSpinner(onView: self.view)
        if let channelsArray = channels?.data {
            self.channels1 = [Channel]()
            for channelItem in channelsArray {
                let number = channelItem.id ?? 0
                let name = channelItem.StationName ?? ""
                let logo = channelItem.logo ?? ""
                let url = channelItem.url ?? ""
                let channel = Channel(number: number, name: name,logo:logo,url:url)
                self.channels1?.append(channel)

                if let programsArray = channelItem.programs {
                    channel.programs = [Program]()
                    for programItem in programsArray {

                        if !(Utility.dayDifference(from: programItem.end ?? 0) ?? false)
                        {
                            break
                        }
//                        let filtered = self.channelsList?.data?.filter{ ($0.genrename?.contains("News") ?? false) }
//                                         let contains = filtered?.contains(where: { $0.callSign == "CNBCWLD" })
                        let title = programItem.title ?? ""
                        let start = Utility.getDateFromTimeStamp(programItem.start ?? 0) ?? 0
                        let end = Utility.getDateFromTimeStamp(programItem.end ?? 0) ?? 0
                        let program = Program(title: title, schedule: Schedule(start: TimeInterval(start), end: TimeInterval(end)))
                        channel.programs?.append(program)
                    }
                }
            }
            if let _ = self.channels1 {
                // print(self.channels!)
            }



        }
        setCollectionViewData()
    }

  
    //MARK: Actions
    @IBAction func searchAction(_ sender:UIButton){
        
        let viewVC =  SearchVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        let searchController = UISearchController(searchResultsController: viewVC)
        searchController.searchResultsUpdater = viewVC
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Channels)", comment: "")

        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")
        viewVC.channelList = self.channels
        guard let rootViewController = view.window?.rootViewController else { fatalError("Unable to get root view controller.") }
        viewVC.channelList = self.channels
        rootViewController.present(searchController, animated: true, completion: nil)
    }
    
    @IBAction func settingsAction(_ sender:UIButton){
//
        let settingVc = HomeNewVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        settingVc.channelList = self.channels
        settingVc.genrsList = self.channelsList
        self.navigationController?.pushViewController(settingVc, animated: true)
//        let settingVc = SettingsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
//
//                self.navigationController?.pushViewController(settingVc, animated: true)
        
    }

    @IBAction func categoriesAction(_ sender:UIButton) {

        switch sender.tag {
        case CategoriesSelect.all.rawValue:

            self.loadData()
        case  CategoriesSelect.recent.rawValue:
            self.loadData()
        case  CategoriesSelect.myChannels.rawValue:
            self.loadData()
        case  CategoriesSelect.sports.rawValue:
            self.loadData()
        case  CategoriesSelect.news.rawValue:
            self.loadData()
        case  CategoriesSelect.movies.rawValue:
            self.loadData()
        case  CategoriesSelect.kids.rawValue:
            self.loadData()
        default:
            return

        }
    }
}

// MARK:  Timer

extension HomeVC {
    func startTimer() {
        timer = Timer.scheduledTimer(withTimeInterval: 1, repeats: true) { timer in
            // Update the current time indicator here.
            
        }
    }
    
    func invalidateTimer() {
        if let _ = timer {
            timer!.invalidate()
            timer = nil
        }
    }
}

// MARK:  UICollectionViewDataSource

extension HomeVC: UICollectionViewDataSource {
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        if let count = channels1?.count{
            return count + 1 // Number of channels + Time headers
        }
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if section == 0 {
            return timeIntervals.count
        } else {
            if let count = self.channels1?[section - 1].programs?.count {
                return count + 1
            }
        }
        return 0
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        let cell: EPGCollectionViewCell = collectionView.dequeueReusableCell(withReuseIdentifier: "epgCell", for: indexPath) as! EPGCollectionViewCell
        cell.titleLabel.text = ""
        
        if indexPath.section == 0 { // Time
            let timeCell = collectionView.dequeueReusableCell(withReuseIdentifier: "timeCell", for: indexPath) as! TimeCollectionViewCell
            let time = self.timeIntervals[indexPath.row]
            timeCell.timeLabel.text = time
            return timeCell

        }
        else
        {
            if indexPath.row == 0 { // Channels
                let channelCell = collectionView.dequeueReusableCell(withReuseIdentifier: "channelCell", for: indexPath) as! ChannelCollectionViewCell
                if let channel = self.channels1?[indexPath.section - 1] {
                       channelCell.configureWith(channel: channel)
                    return channelCell
                }
                // cell.backgroundColor = UIColor.orange
            } else {
                if let channel = self.channels1?[indexPath.section - 1] {
                    if let program = channel.programs?[indexPath.row - 1] {
                        let programCell = collectionView.dequeueReusableCell(withReuseIdentifier: "programCell", for: indexPath) as! ProgramCollectionViewCell
                         programCell.configureWith(program: program)
                        return programCell
                    }
                }
            }
        }
        return UICollectionViewCell()
    }
}

// MARK:  UICollectionViewDelegate

extension HomeVC: UICollectionViewDelegate {
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        if indexPath.section == 0 {
            // Do nothing.
        }
        else if indexPath.row == 0 { // Channels
            if let channel = self.channels1?[indexPath.section - 1] {
                //print(channel)
                let viewController = PlayerVC.instantiateFromAppStoryboard(appStoryboard: .Main)
                guard let url = URL(string: channel.url) else {return}
                viewController.url = url
                self.present(viewController, animated:true, completion:nil)
                
            }
        } else { // Programs
            if let channel = self.channels1?[indexPath.section - 1] {
                if let program = channel.programs?[indexPath.row - 1] {
                    //   print(program)
                }
            }
        }
    }
}

extension HomeVC:HandleError
{
    func handleError(_ message: String) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!")
    }

}


