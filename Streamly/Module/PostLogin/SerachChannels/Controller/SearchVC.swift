//
//  SearchVC.swift
//  Streamly
//
//  Created by Rohit Singh on 5/29/20.
//  Copyright © 2020 Rohit Singh. All rights reserved.
//

import UIKit

class SearchVC: UIViewController, UISearchResultsUpdating,UICollectionViewDelegate,UICollectionViewDataSource {
    // MARK: Properties
    
    static let storyboardIdentifier = "SearchVC"
    
    private let cellComposer = DataItemCellComposer()
    
    private let allDataItems = DataItem.sampleItems
    
    private var filteredDataItems = DataItem.sampleItems
    
    @IBOutlet weak var collectionView:UICollectionView!
    
    var filterString = "" {
        didSet {
            // Return if the filter string hasn't changed.
            guard filterString != oldValue else { return }
            
            // Apply the filter or show all items if the filter string is empty.
            if filterString.isEmpty {
                filteredDataItems = allDataItems
            }
            else {
                filteredDataItems = allDataItems.filter { $0.title.localizedStandardContains(filterString) }
            }
            
            // Reload the collection view to reflect the changes.
            collectionView?.reloadData()
        }
    }
    override func viewDidLoad() {
        super.viewDidLoad()
        self.collectionView.layoutIfNeeded()
        self.collectionView.layoutSubviews()
    }
    // MARK: UIViewController
    
    //    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
    //        super.prepare(for: segue, sender: sender)
    //
    //        // Check if a DataItemViewController is being presented.
    ////        if let dataItemViewController = segue.destination as? DataItemViewController {
    ////            // Pass the selected `DataItem` to the `DataItemViewController`.
    ////            guard let indexPath = collectionView?.indexPathsForSelectedItems?.first else { fatalError("Expected a cell to have been selected") }
    ////            dataItemViewController.configure(with: filteredDataItems[indexPath.row])
    ////        }
    //    }
    
    // MARK: UICollectionViewDataSource
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return 1
    }
    
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        return filteredDataItems.count
    }
    
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        // Dequeue a cell from the collection view.
        return collectionView.dequeueReusableCell(withReuseIdentifier: DataItemCollectionViewCell.reuseIdentifier, for: indexPath)
    }
    
    // MARK: UICollectionViewDelegate
    
    func collectionView(_ collectionView: UICollectionView, willDisplay cell: UICollectionViewCell, forItemAt indexPath: IndexPath) {
        guard let cell = cell as? DataItemCollectionViewCell else { fatalError("Expected to display a `DataItemCollectionViewCell`.") }
        let item = filteredDataItems[(indexPath as NSIndexPath).row]
        
        // Configure the cell.
        cellComposer.compose(cell, withDataItem: item)
    }
    
    // MARK: UISearchResultsUpdating
    
    func updateSearchResults(for searchController: UISearchController) {
        filterString = searchController.searchBar.text ?? ""
    }
}

