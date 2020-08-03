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
    var recentData: RecentData?
    var filteredList:[ChannelDetails]?
    {
        didSet{
            self.setupEPGLayout()
        }
    }
    var selectedCategories = 0
    var recentIDs:[Int] = []
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
        // self.configureButtons()
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

    private func configureButtons()
    {

        //  self.allButton.set


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

    private func setupEPGLayout()
    {
        let layout = EpgGridLayout()
        layout.channels = filteredList
        layout.epgStartTime = NSDate(timeIntervalSince1970: filteredList?[0].programs?[0].start ?? 0) as Date
        layout.epgEndTime = layout.epgStartTime.addingTimeInterval(layout.weekTimeInterval)
        layout.layoutInfo = NSMutableDictionary()
        layout.framesInfo = NSMutableDictionary()
        collectionView.setCollectionViewLayout(layout, animated: false)
        self.tableView.reloadData()
        self.tableView.layoutIfNeeded()
        collectionView.reloadData()
        self.collectionView.layoutIfNeeded()
        self.collectionView.scrollToItem(at: IndexPath(row: 0, section: 0), at: .left, animated: false)

    }

    private func selectCategories(_ sender:Int)
    {
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

    private func initVMRecent() {

        viewModel.recentData = { recentModel in

            self.recentData = recentModel
            self.recentIDs = self.recentData?.data?.map { $0.id ?? 0} ?? []
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
            self.recentIDs.append(channel.id ?? 0)
            guard let url = URL(string: channel.url ?? "") else {return}
            viewController.url = url
            viewController.delegatee = self
            viewController.idS = self.recentIDs.map(String.init).joined(separator: ",")
            viewController.isfromRcent = selectedCategories == 12 ? true : false
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

        guard let channel = filteredList?[indexPath.section], let programs = channel.programs else { return UICollectionViewCell() }

        programCell.configureWithModel(program: programs[indexPath.item])
        return programCell
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
