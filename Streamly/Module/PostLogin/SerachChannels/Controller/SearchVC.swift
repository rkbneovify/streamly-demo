//
//  SearchVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/29/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit
import AVKit

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
            }
            else {
                filtterdChannelList = self.channelList?.data?.filter { ($0.StationName?.localizedStandardContains(filterString) ?? false) }
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
     var filtterdChannelList:[ChannelDetails]?
     lazy var viewModel: SearchDataViewModel = {
          return SearchDataViewModel()
      }()

    //  MARK: Outlets

    @IBOutlet weak var collectionView:UICollectionView!
    

    
    //  MARK: LifeCycle

    override func viewDidLoad() {
        super.viewDidLoad()
        self.initialSetup()

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
//          //  debugPrint(self.channelList?.message?.data?[0])
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
        guard let item = filtterdChannelList?[(indexPath as NSIndexPath).row] else {return}
        let viewController = PlayerVC.instantiateFromAppStoryboard(appStoryboard: .Main)
        guard let url = URL(string: item.url ?? "") else {return}
        viewController.url = url
        self.present(viewController, animated: true, completion: nil)
        
        
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
    func handleError(_ message: String) {
        self.removeSpinner()
        self.showSimpleAlertWithMessage("Something went wrong!")
    }

}



