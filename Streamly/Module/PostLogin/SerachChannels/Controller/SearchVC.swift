//
//  SearchVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/29/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit


class SearchVC: UIViewController {

    // MARK: Properties
    
    static let storyboardIdentifier = "SearchVC"
  //  var searchController: UISearchController!
    private let cellComposer = DataItemCellComposer()
    var filterString = "" {
        didSet {
            // Return if the filter string hasn't changed.
            guard filterString != oldValue else { return }

            // Apply the filter or show all items if the filter string is empty.
            if filterString.isEmpty {
                filteredChannelListInfo = self.searchInfoList?.data ?? []
            } else {
                filteredChannelListInfo = self.searchInfoList?.data?.filter({($0.callSign?.lowercased().contains(filterString.lowercased()) ?? false) || $0.id == (Int(filterString))})
            }

            // Reload the collection view to reflect the changes.
            collectionView?.reloadData()
        }
    }
    var searchInfoList: ChannelData? {
        didSet {
            self.filteredChannelListInfo = searchInfoList?.data ?? []
        }
    }
//    var channelList:EPGResponse?
//      {
//          didSet
//          {
//            self.filtterdChannelList = channelList?.data?.data ?? []
//          }
//      }
     var geoData:GeoFenceAddress?
     lazy var viewModelEpg: EPGViewModel = {
          return EPGViewModel()
      }()
//     var filtterdChannelList:[ChannelDetails]?
    var filteredChannelListInfo: [ChannelArray]?
     lazy var viewModel: SearchDataViewModel = {
          return SearchDataViewModel()
      }()

    var videoPlaybackViewController: VideoPlaybackViewController
    override init(nibName nibNameOrNil: String?, bundle nibBundleOrNil: Bundle?) {
        videoPlaybackViewController = VideoPlaybackViewController()
        super.init(nibName: nibNameOrNil, bundle: nibBundleOrNil)
    }

    required init?(coder: NSCoder) {
        videoPlaybackViewController = VideoPlaybackViewController()
        super.init(coder: coder)
    }

    //  MARK: Outlets

    @IBOutlet weak var collectionView:UICollectionView!
    

    
    //  MARK: LifeCycle

    override func viewDidLoad() {
        super.viewDidLoad()
        self.initialSetup()

    }

    override func viewWillAppear(_ animated: Bool) {
        super.viewWillAppear(true)
        DispatchQueue.global(qos: .background).async
            {
                self.makeApiGeoFencing()

        }
    }

    //  MARK: Helper

    private func initialSetup()
    {

        self.view.backgroundColor = UIColor(patternImage: UIImage(named: "bacgroundImage")!)
    //    self.collectionView.backgroundView = UIView()
        self.collectionView.reloadData()
        self.collectionView.layoutIfNeeded()
        self.collectionView.layoutSubviews()
        self.makeApiCall()
        self.initVM()
    }

    private func makeApiGeoFencing(){
           viewModelEpg.postAddressData()
           initVMGeoData()

       }


    private func initVMGeoData() {

           viewModelEpg.geoData = { geoDataModel in

               self.geoData = geoDataModel

           }

       }

    private func makeApiCall(){
        self.showSpinner(onView: self.view)
          viewModel.delegate = self
          viewModel.getAllChannelListForSearch()

      }

      private func initVM() {

          viewModel.searchData = { contactModel in
            self.searchInfoList = contactModel
            self.filteredChannelListInfo = contactModel.data
              self.filterString = ""
          //  //debugPrint(self.channelList?.message?.data?[0])
              self.collectionView.reloadData()
              self.removeSpinner()


          }

      }

}

// MARK: UICollectionViewDataSource

extension SearchVC:UICollectionViewDataSource
{
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if (filteredChannelListInfo?.isEmpty ?? true || filteredChannelListInfo == nil){
            self.collectionView.setEmptyMessage("No record's found")
        } else {
            self.collectionView.restore()
        }
        return filteredChannelListInfo?.count ?? 0

    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        // Dequeue a cell from the collection view.
        return collectionView.dequeueReusableCell(withReuseIdentifier: DataItemCollectionViewCell.reuseIdentifier, for: indexPath)
    }


}

extension SearchVC:UICollectionViewDelegate

{
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        guard let cell = cell as? DataItemCollectionViewCell else { fatalError("Expected to display a `DataItemCollectionViewCell`.") }
        guard let item = filteredChannelListInfo?[(indexPath as NSIndexPath).row] else {return}

        // Configure the cell.
        let chanelInfo = ChannelDetails(id: item.id ?? 0, stationName: item.stationName ?? "", url: item.url ?? "", logo: item.logo ?? "", callSign: item.callSign ?? "", programs: [])
        cellComposer.compose(cell, withDataItem: chanelInfo)
    }

    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {

        let flag = self.geoData?.success ?? false
        guard let item = filteredChannelListInfo?[(indexPath.item)] else {return}
        let chanelInfo = ChannelDetails(id: item.id ?? 0, stationName: item.stationName ?? "", url: item.url ?? "", logo: item.logo ?? "", callSign: item.callSign ?? "", programs: [])
        present(videoPlaybackViewController, animated: true) {
            self.videoPlaybackViewController.channelObject = chanelInfo
            self.videoPlaybackViewController.isAllowedZipCode = flag
            self.videoPlaybackViewController.str = self.geoData?.popupmsg ?? ""
            self.videoPlaybackViewController.isfromRcent = false
            self.videoPlaybackViewController.idS = "\(item.id ?? 0)"
            self.videoPlaybackViewController.isPopAllow = self.geoData?.popupmsgtoggle ?? true
            self.videoPlaybackViewController.filterData = self.filteredChannelListInfo?.map({ obj in
                return ChannelDetails(id: obj.id ?? 0, stationName: obj.stationName ?? "", url: obj.url ?? "", logo: obj.logo ?? "", callSign: obj.callSign ?? "", programs: [])
            })
            self.videoPlaybackViewController.loadAndPlay()
        
    }
  }
}

// MARK: UISearchResultsUpdating

extension SearchVC:UISearchResultsUpdating
{
    
    func updateSearchResults(for searchController: UISearchController) {
        filterString = searchController.searchBar.text ?? ""
    }
}

extension SearchVC:HandleError
{
    
    func successAPI() {
        self.removeSpinner()
    }
    
    func handleError(_ message: String, _ statusCode: Int) {
        self.removeSpinner()
        if statusCode == 401 {
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
        } else if statusCode == 404 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isDeviceNameAdded)
                Utility.logOut()
            }
            return
        }
        
        self.showSimpleAlertWithMessage("Something went wrong!")
    }
}



