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
            self.collectionView.reloadData()
        }
    }
    var channelsList: ChannelListResponse?
    var channels1:[Channel]?
    {
        didSet
        {
            self.removeSpinner()
        }
    }
    lazy var viewModel: EPGViewModel = {
             return EPGViewModel()
         }()
    var timer: Timer?
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
       // self.loadData()

     //   self.setCollectionViewData()
        collectionView.backgroundColor = UIColor.clear
    }

    private func setCollectionViewData()
    {
        let layout = EPGCollectionViewLayout()
        layout.channels = self.channels?.data
        collectionView.setCollectionViewLayout(layout, animated: false)
        collectionView.dataSource = self
        collectionView.delegate = self
        collectionView.layoutIfNeeded()
        collectionView.layoutSubviews()
        self.collectionView.reloadData()
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
        self.goBackButton.setBtnProperties(self.goBackButton)
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
            self.setCollectionViewData()
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
    
//    func loadData() {
//        self.showSpinner(onView: self.view)
//        if let jsonFile = Bundle.main.path(forResource: "Schedule", ofType: "json") {
//            do {
//                let data = try Data(contentsOf: URL(fileURLWithPath: jsonFile), options: .dataReadingMapped)
//                let json = try JSONSerialization.jsonObject(with: data, options: .mutableLeaves)
//                if let jsonDictionary = json as? [String:Any] {
//                    if let channelsArray = jsonDictionary["data"] as? [[String:Any]] {
//                        self.channels1 = [Channel]()
//                        for channelItem in channelsArray {
//                            let number = 0
//                            let name = channelItem["StationName"] as! String
//                            let channel = Channel(number: number, name: name)
//                            self.channels1?.append(channel)
//
//                            if let programsArray = channelItem["programs"] as? [[String:Any]] {
//                                channel.programs = [Program]()
//                                for programItem in programsArray {
//                                    let title = programItem["title"] as! String
//                                    let start = programItem["start"] as! Int
//                                    let end = programItem["end"] as! Int
//                                    let date1 = getDateFromTimeStamp(Double(start)) ?? 0
//                                     let date2 = getDateFromTimeStamp(Double(end)) ?? 0
////                                    let x = self.commonDate(start)
////                                   let y = self.commonDate(end)
//
//                                    let program = Program(title: title, schedule: Schedule(start: TimeInterval(date1), end: TimeInterval(date2)))
//                                    channel.programs?.append(program)
//                                }
//                            }
//                        }
//                        if let _ = self.channels1 {
//                           // print(self.channels!)
//                        }
//                    }
//                }
//            } catch  {
//                print(error)
//            }
//        }
//        collectionView.reloadData()
//    }
//    func getDateFromTimeStamp(_ timeStamp : Double) -> Int? {
//
//        let date = NSDate(timeIntervalSince1970: timeStamp)
//
//        let dayTimePeriodFormatter = DateFormatter()
//        dayTimePeriodFormatter.dateFormat = "HH:mm"
//        dayTimePeriodFormatter.locale = Locale(identifier: "en_US_POSIX")
//        dayTimePeriodFormatter.timeZone = TimeZone(abbreviation: "UTC")
//     // UnComment below to get only time
//    //  dayTimePeriodFormatter.dateFormat = "hh:mm a"
//
//        let dateString = dayTimePeriodFormatter.string(from: date as Date)
//
//        let fullNameArr = dateString.components(separatedBy: ":")
//        guard let hour = Int(fullNameArr[0]) else {return nil}
//        guard let min = Int(fullNameArr[1]) else {return nil}
//
//
//        return ((hour * 60 * 60) + (min * 60))
//    }
//    
    //MARK: Actions
    @IBAction func searchAction(_ sender:UIButton){
        
        let viewVC =  SearchVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        let searchController = UISearchController(searchResultsController: viewVC)
        searchController.searchResultsUpdater = viewVC
        searchController.searchBar.placeholder = NSLocalizedString("Enter keyword (e.g. Channels)", comment: "")
        // Contain the `UISearchController` in a `UISearchContainerViewController`.
        let searchContainer = UISearchContainerViewController(searchController: searchController)
        searchContainer.title = NSLocalizedString("Search", comment: "")
        self.navigationController?.pushViewController(searchContainer, animated: true)
        
        
    }
    
    @IBAction func settingsAction(_ sender:UIButton){
        
        let settingVc = SettingsVC.instantiateFromAppStoryboard(appStoryboard: .Extended)
        self.navigationController?.pushViewController(settingVc, animated: false)
        
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
        if let count = channels?.data?.count{
            return count + 1 // Number of channels + Time headers
        }
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if section == 0 {
            return timeIntervals.count
        } else {
            if let count = self.channels?.data?[section - 1].programs?.count {
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
            if let channel = self.channels?.data?[indexPath.section - 1] {
                channelCell.configureWith(channel: channel)
                return channelCell
            }
           // cell.backgroundColor = UIColor.orange
        } else {
            if let channel = self.channels?.data?[indexPath.section - 1] {
                if let program = channel.programs?[indexPath.row - 1] {
                    let programCell = collectionView.dequeueReusableCell(withReuseIdentifier: "programCell", for: indexPath) as! ProgramCollectionViewCell
                    programCell.configureWith(program: program)
                    return programCell
                }
            }
        }
        }
        return cell
    }
}

// MARK:  UICollectionViewDelegate

extension HomeVC: UICollectionViewDelegate {
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        if indexPath.section == 0 {
            // Do nothing.
        }
        else if indexPath.row == 0 { // Channels
            if let channel = self.channels?.data?[indexPath.section - 1] {
                //print(channel)
                let viewController = PlayerVC.instantiateFromAppStoryboard(appStoryboard: .Main)
                guard let url = URL(string: channel.url ?? "") else {return}
                viewController.url = url
                self.present(viewController, animated:true, completion:nil)
                
            }
        } else { // Programs
            if let channel = self.channels?.data?[indexPath.section - 1] {
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


