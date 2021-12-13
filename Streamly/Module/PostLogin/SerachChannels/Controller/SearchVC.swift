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
                filtterdChannelList = self.channelList?.data
            } else {
                filtterdChannelList = self.channelList?.data?.filter({($0.StationName?.lowercased().contains(filterString.lowercased()) ?? false) || $0.id == (Int(filterString))})
            }

            // Reload the collection view to reflect the changes.
            collectionView?.reloadData()
        }
    }
    var channelList:EPGResponse?
      {
          didSet
          {
            self.filtterdChannelList = channelList?.data
          }
      }
     var geoData:GeoFenceAddress?
     lazy var viewModelEpg: EPGViewModel = {
          return EPGViewModel()
      }()
     var filtterdChannelList:[ChannelDetails]?
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
//        self.makeApiCall()
//        self.initVM()
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

//    private func makeApiCall(){
//        self.showSpinner(onView: self.view)
//          viewModel.delegate = self
//          viewModel.getSearchData()
//
//      }
//
//      private func initVM() {
//
//          viewModel.searchData = { contactModel in
//            self.channelList = contactModel
//            self.filtterdChannelList = contactModel.message?.data
//          //  //debugPrint(self.channelList?.message?.data?[0])
//
//              self.removeSpinner()
//
//
//          }
//
//      }

}

// MARK: UICollectionViewDataSource

extension SearchVC:UICollectionViewDataSource
{
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        if (filtterdChannelList?.isEmpty ?? true || filtterdChannelList == nil){
            self.collectionView.setEmptyMessage("No record's found")
        } else {
            self.collectionView.restore()
        }
        return filtterdChannelList?.count ?? 0

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
        guard let item = filtterdChannelList?[(indexPath as NSIndexPath).row] else {return}

        // Configure the cell.
        cellComposer.compose(cell, withDataItem: item)
    }

    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {

        let flag = self.geoData?.success ?? false
        guard let item = filtterdChannelList?[(indexPath.item)] else {return}
        present(videoPlaybackViewController, animated: true) {
            self.videoPlaybackViewController.channelObject = item
            self.videoPlaybackViewController.isAllowedZipCode = flag
            self.videoPlaybackViewController.str = self.geoData?.popupmsg ?? ""
            self.videoPlaybackViewController.isfromRcent = false
            self.videoPlaybackViewController.idS = "\(item.id ?? 0)"
            self.videoPlaybackViewController.isPopAllow = self.geoData?.popupmsgtoggle ?? true
            self.videoPlaybackViewController.filterData = self.filtterdChannelList
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
        if statusCode == 401 {
            showAlert(withMessage: "Your session has expired. You have to log in again to proceed further.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
        } else if statusCode == 402 {
            showAlert(withMessage: "We're having some trouble with your current subscription. We'll try again, but in meantime you may want to update your subscription. For more help, please visit streamlytv.com or contact at https://streamlytv.com/helpandsupport.",setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
            return
        } else if statusCode == 404 {
            showAlert(withMessage: message,setFirstButtonTitle: "Logout") { _ in
                Utility.setBoolInUserDefaultData(value: false, key: Constant.UserDefault.isLogin)
                Utility.logOut()
            }
            return
        }
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!")
    }
}



