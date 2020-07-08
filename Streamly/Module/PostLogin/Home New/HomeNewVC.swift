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
    var filteredList:[ChannelDetails]?
    {
        didSet{
            self.setupEPGLayout()
        }
    }
    var selectedCategories = 0
    var timer:Timer?
    lazy var viewModel: EPGViewModel = {
           return EPGViewModel()
       }()
    override var preferredFocusedView: UIView? {
        get {
            return self.allButton
        }
    }

    // MARK: - Outlets
    @IBOutlet weak var tableView:UITableView!
    @IBOutlet weak var collectionView:UICollectionView!
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

    // MARK: - LifeCycle
    override func viewDidLoad() {
        super.viewDidLoad()
        self.intialSetup()

        // Do any additional setup after loading the view.
    }

    override func viewWillLayoutSubviews() {
        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
    }

    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        startTimer()
    }

    override func viewDidDisappear(_ animated: Bool) {
        super.viewDidDisappear(animated)
        invalidateTimer()
    }

     // MARK: - Helper
     private func intialSetup()
     {
        self.tableView.dataSource = self
        self.tableView.delegate = self
        let programNib = UINib(nibName: "ProgramCollectionViewCell", bundle: Bundle.main)
        collectionView.register(programNib, forCellWithReuseIdentifier: "programCell")
        collectionView.delegate = self
        collectionView.dataSource = self
        self.configureButtons()
        self.makeApiCall()
               self.initVM()
               DispatchQueue.global(qos: .background).async
                   {
                       self.makeApiCallChannelList()
                       self.initVMList()
               }


    }

    private func configureButtons()
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

    private func setupEPGLayout()
    {
        let layout = EpgGridLayout()
        layout.channels = filteredList
        layout.epgStartTime = NSDate(timeIntervalSince1970: filteredList?[0].programs?[0].start ?? 0) as Date
        layout.epgEndTime = layout.epgStartTime.addingTimeInterval(layout.weekTimeInterval)
        layout.layoutInfo = NSMutableDictionary()
        layout.framesInfo = NSMutableDictionary()
        collectionView.setCollectionViewLayout(layout, animated: true)
        collectionView.reloadData()
        self.tableView.reloadData()
        self.tableView.layoutIfNeeded()
        self.collectionView.layoutIfNeeded()
    }

    private func selectCategories(_ sender:Int)
    {
        switch sender {
        case CategoriesSelect.all.rawValue:
            guard  self.selectedCategories != CategoriesSelect.all.rawValue else {return}
            self.filteredList = self.channelList?.data
            self.selectedCategories = CategoriesSelect.all.rawValue
        case  CategoriesSelect.recent.rawValue:
            debugPrint("recent")
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

     // MARK: - IBActions
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

extension HomeNewVC : UITableViewDelegate, UITableViewDataSource {
    func numberOfSections(in tableView: UITableView) -> Int {
        return 1
    }

    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return 100
    }

    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {

        return filteredList?.count ?? 0
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        guard let cell =  tableView.dequeueReusableCell(withIdentifier: ChannelListCell.reuseIdentifier, for: indexPath) as? ChannelListCell else {return UITableViewCell()}
        cell.configureCell(filteredList?[indexPath.row].logo ?? "", filteredList?[indexPath.row].CallSign ?? "", item: filteredList?[indexPath.row].id ?? 0)
        return cell
    }

    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let viewController = PlayerVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        if let channel = self.filteredList?[indexPath.row]
        {
            guard let url = URL(string: channel.url ?? "") else {return}
            viewController.url = url
            self.present(viewController, animated:true, completion:nil)
        }
    }
}

extension HomeNewVC : UICollectionViewDelegate, UICollectionViewDataSource {
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        guard let channels = filteredList else { return 0}
        return channels.count
    }

    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        guard let channel = filteredList?[section], let programs = channel.programs else { return 0 }
        return programs.count
    }

    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        guard let programCell = collectionView.dequeueReusableCell(withReuseIdentifier: "programCell", for: indexPath) as? ProgramCollectionViewCell else {return UICollectionViewCell()}
//
//        programCell.layer.borderWidth = 1
//        programCell.layer.borderColor = UIColor.lightGray.cgColor

        guard let channel = filteredList?[indexPath.section], let programs = channel.programs else { return UICollectionViewCell() }

        programCell.configureWithModel(program: programs[indexPath.item])
        return programCell
     //   cell.programTIme.text = ""
//        let activityView = UIActivityIndicatorView(style: .large)
//           activityView.center = CGPoint(x: cell.contentView.frame.size.width / 2, y: cell.contentView.frame.size.height / 2)
//           cell.contentView.addSubview(activityView)
     //  cell.contentView.center = CGPoint(x: cell.contentView.frame.size.width / 2, y: cell.contentView.frame.size.height / 2)


    }

    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
//        let ch = channels![indexPath.section]
//        let program = ch.programs![indexPath.row]
//        let message = "\(ch.channelName!) - \(program.name!)"
//        let alert = UIAlertController(title: "Alert", message: message, preferredStyle:.alert)
//        let alertAction = UIAlertAction(title: "Ok", style: .default) {  (alert:UIAlertAction) in
//            self.dismiss(animated: true, completion: nil)
//        }
//        alert.addAction(alertAction)
//        self .present(alert, animated: true, completion: nil)
    }
}

extension HomeNewVC : UIScrollViewDelegate {
    func scrollViewDidScroll(_ scrollView: UIScrollView) {
        if (scrollView == tableView) {
            collectionView.contentOffset = CGPoint(x: collectionView.contentOffset.x, y: tableView.contentOffset.y)
        }
        else {
            tableView.contentOffset = CGPoint(x: tableView.contentOffset.x, y: collectionView.contentOffset.y)
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
